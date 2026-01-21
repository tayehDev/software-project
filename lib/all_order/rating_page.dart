import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:hexcolor/hexcolor.dart';
import 'package:fluttertoast/fluttertoast.dart';

import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';

final Color primaryColor = HexColor("#011432");
final Color secondaryColor = HexColor("#e75423");
final Color backgroundColor = HexColor("#f8f8f8");

class RatingPage extends StatefulWidget {
  final int orderId;
  const RatingPage({Key? key, required this.orderId}) : super(key: key);

  @override
  State<RatingPage> createState() => _RatingPageState();
}

class _RatingPageState extends State<RatingPage> {
  late Future<Map<String, dynamic>> ratingFuture;

  // قيم النجوم الافتراضية
  int sellerToBuyer = 0;
  int buyerToSeller = 0;
  int courierToBuyer = 0;

  @override
  void initState() {
    super.initState();
    ratingFuture = fetchRating();
  }

  /// =======================
  /// 📡 FETCH RATINGS
  /// =======================
  Future<Map<String, dynamic>> fetchRating() async {
    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    final response = await http.post(
      uri,
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "action": "show_order_rating_details",
        "order_id": widget.orderId,
      }),
    );

    final data = jsonDecode(response.body);

    // خزن القيم بالمتغيرات لتحديث النجوم عند تحميل الصفحة
    if (data['data'] != null && data['data'].isNotEmpty) {
      final row = data['data'][0];
      sellerToBuyer = row['evaluate_buyer_by_seller'] ?? 0;
      buyerToSeller = row['evaluate_seller_by_buyer'] ?? 0;
      courierToBuyer = row['evaluate_buyer_by_courier'] ?? 0;
    }

    return data['data'] != null && data['data'].isNotEmpty
        ? data['data'][0]
        : {};
  }

  /// =======================
  /// 💾 SAVE RATINGS
  /// =======================
Future<void> submitRating() async {
  Map<String, dynamic> body = {
    "action": "insert_order_rating",
    "by_user_id": globals.current_user_id,
    "by_user_role": globals.current_user_role_account,
    "order_id": widget.orderId,
  };

  if (globals.current_user_role_account == 2) {
    body["evaluate_buyer_by_seller"] = sellerToBuyer;
  }

  if (globals.current_user_role_account == 3) {
    body["evaluate_seller_by_buyer"] = buyerToSeller;
  }

  if (globals.current_user_role_account == 4) {
    body["evaluate_buyer_by_courier"] = courierToBuyer;
  }

  final uri = useHttps
      ? Uri.https(config_URL, config_unencodedPath)
      : Uri.http(config_URL, config_unencodedPath);

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode(body),
  );

  if (response.statusCode == 200) {
    Fluttertoast.showToast(msg: "Rating saved");
    setState(() {
      ratingFuture = fetchRating();
    });
  }
}


  /// =======================
  /// 🖥 UI
  /// =======================
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      bottomNavigationBar: footer(context),
      backgroundColor: backgroundColor,
      body: FutureBuilder<Map<String, dynamic>>(
        future: ratingFuture,
        builder: (context, snapshot) {
          if (!snapshot.hasData) {
            return const Center(child: CircularProgressIndicator());
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                _buildRatingRow(
                  "Seller → Buyer",
                  sellerToBuyer,
                  globals.current_user_role_account == 2,
                  (val) => setState(() => sellerToBuyer = val),
                ),
                const SizedBox(height: 12),
                _buildRatingRow(
                  "Buyer → Seller",
                  buyerToSeller,
                  globals.current_user_role_account == 3,
                  (val) => setState(() => buyerToSeller = val),
                ),
                const SizedBox(height: 12),
                _buildRatingRow(
                  "Courier → Buyer",
                  courierToBuyer,
                  globals.current_user_role_account == 4,
                  (val) => setState(() => courierToBuyer = val),
                ),
                const SizedBox(height: 20),

                if (globals.current_user_role_account == 2||globals.current_user_role_account == 3||globals.current_user_role_account == 4)
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: secondaryColor,
                      ),
                      onPressed: submitRating,
                      child: const Text(
                        'Save Rating',
                        style: TextStyle(color: Colors.white),
                      ),
                    ),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }

  /// =======================
  /// ⭐ STAR WIDGET
  /// =======================
  Widget _buildRatingRow(
      String title, int currentValue, bool editable, Function(int) onChange) {
    return Card(
      elevation: 3,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: TextStyle(
                  fontSize: 16, fontWeight: FontWeight.bold, color: primaryColor),
            ),
            const SizedBox(height: 8),
            Row(
              children: List.generate(5, (index) {
                int starIndex = index + 1;
                return IconButton(
                  icon: Icon(
                    starIndex <= currentValue
                        ? Icons.star
                        : Icons.star_border,
                    color: secondaryColor,
                  ),
                  onPressed: editable
                      ? () => onChange(starIndex)
                      : null,
                );
              }),
            ),
          ],
        ),
      ),
    );
  }
}

