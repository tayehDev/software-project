import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:hexcolor/hexcolor.dart';
import 'package:fluttertoast/fluttertoast.dart';

import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';

/// =======================
/// 🎨 COLORS
/// =======================
final Color primaryColor = HexColor("#011432");
final Color secondaryColor = HexColor("#e75423");
final Color backgroundColor = HexColor("#f8f8f8");

class TrackingPage extends StatefulWidget {
  final int orderId;
  const TrackingPage({Key? key, required this.orderId}) : super(key: key);

  @override
  State<TrackingPage> createState() => _TrackingPageState();
}

class _TrackingPageState extends State<TrackingPage> {
  late Future<List<dynamic>> trackingFuture;

  final Map<int, String> stagesMap = {
    0: "Processing",
    1: "To Sorting Center",
    2: "At Sorting Center",
    3: "To Buyer",
    4: "Delivered",
  };

  List<int> selectedStages = [];

  @override
  void initState() {
    super.initState();
    trackingFuture = fetchTracking();
  }

  /// =======================
  /// 📡 FETCH
  /// =======================
  Future<List<dynamic>> fetchTracking() async {
    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    final response = await http.post(
      uri,
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "action": "show_order_tracking",
        "order_id": widget.orderId,
      }),
    );

    final data = jsonDecode(response.body);
    return data['dataView'] ?? [];
  }

  /// =======================
  /// 💾 SAVE
  /// =======================
  Future<void> submitTracking() async {
    if (selectedStages.isEmpty) return;

    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    final response = await http.post(
      uri,
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        "action": "insert_order_tracking",
        "by_user_id": globals.current_user_id,
        "by_user_role": globals.current_user_role_account,
        "order_id": widget.orderId,
        "stages": selectedStages,
      }),
    );

    if (response.statusCode == 200) {
      Fluttertoast.showToast(msg: "Tracking updated");
      setState(() {
        selectedStages.clear();
        trackingFuture = fetchTracking();
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
      body: FutureBuilder<List<dynamic>>(
        future: trackingFuture,
        builder: (context, snapshot) {
          if (!snapshot.hasData) {
            return const Center(child: CircularProgressIndicator());
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                _buildTimeline(snapshot.data!),
                const SizedBox(height: 30),

                /// 🔐 فقط role = 4
                if (globals.current_user_role_account == 4)
                  _buildStageSelector(),
              ],
            ),
          );
        },
      ),
    );
  }

  /// =======================
  /// 🕒 TIMELINE
  /// =======================
  Widget _buildTimeline(List list) {
    if (list.isEmpty) {
      return const Text("Not started");
    }

    return Column(
      children: list.map((row) {
        return Card(
          elevation: 3,
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            leading: CircleAvatar(
              backgroundColor: secondaryColor,
              child: const Icon(Icons.local_shipping, color: Colors.white),
            ),
            title: Text(
              row['stage_name'] ?? "Unknown",
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: primaryColor,
              ),
            ),
            subtitle: Text(row['at_datetime']),
          ),
        );
      }).toList(),
    );
  }

  /// =======================
  /// ☑ STAGE SELECTOR
  /// =======================
  Widget _buildStageSelector() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Update Tracking Status',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: primaryColor,
              ),
            ),
            const SizedBox(height: 12),

            ...stagesMap.entries.map((entry) {
              return CheckboxListTile(
                value: selectedStages.contains(entry.key),
                title: Text(entry.value),
                activeColor: secondaryColor,
                onChanged: (val) {
                  setState(() {
                    val == true
                        ? selectedStages.add(entry.key)
                        : selectedStages.remove(entry.key);
                  });
                },
              );
            }).toList(),

            const SizedBox(height: 10),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: secondaryColor,
                ),
                onPressed:
                    selectedStages.isEmpty ? null : submitTracking,
                child: const Text(
                  'Save Tracking',
                  style: TextStyle(color: Colors.white),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

