import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:hexcolor/hexcolor.dart';

// Project imports
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/all_order/tracking_page.dart';
import 'package:platform_stores/all_order/rating_page.dart';
import 'package:platform_stores/all_order/MapRoutePage.dart';
import 'package:platform_stores/all_order/MapRouteWebPage.dart';


/// =======================
/// 🎨 Colors
/// =======================
final Color primaryColor = HexColor("#011432");      // Dark Blue
final Color secondaryColor = HexColor("#e75423");    // Orange-Red
final Color lightBackground = HexColor("#f8f8f8");   // Light Background
final Color statusPending = Colors.orange;
final Color statusApproved = Colors.green;
final Color statusRejected = Colors.red;

class ShowOneRowAllOrder extends StatelessWidget {
  final int orderId;

  const ShowOneRowAllOrder(this.orderId, {Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      bottomNavigationBar: footer(context),
      body:Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [lightBackground, Colors.white, lightBackground],
          ),
        ),
        child:FutureBuilder<Map<String, dynamic>>(
        future: _fetchOrderDetails(orderId),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(
              child: Text(
                'Error: ${snapshot.error}',
                style: TextStyle(color: secondaryColor),
              ),
            );
          }
          if (!snapshot.hasData) {
            return const Center(child: Text('No data available'));
          }

          final allOrderData = snapshot.data!;
          return _AllOrderDetailView(allOrderData: allOrderData);
        },
      ),
      ),
    );
  }

  Future<Map<String, dynamic>> _fetchOrderDetails(int orderId) async {
    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    final response = await http.post(
      uri,
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        "action": "show_one_row_allOrders",
        "by_user_id": globals.current_user_id,
        "order_id": orderId
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body)['dataView'][0];
    } else {
      throw Exception('Failed to load order details');
    }
  }
}

class _AllOrderDetailView extends StatelessWidget {
  final Map<String, dynamic> allOrderData;

  const _AllOrderDetailView({required this.allOrderData, Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildProductImage(),
          const SizedBox(height: 24),
          _buildDetailsCard(),
          const SizedBox(height: 24),
          _buildStatusSection(),
          const SizedBox(height: 24),
          _buildPriceSection(),
          const SizedBox(height: 30),

          /// ✅ زر التتبع
          _buildTrackingButton(context),
          const SizedBox(height: 10),
          _buildRatingButton(context),
          const SizedBox(height: 10),
          _buildMapingButton(context),
          const SizedBox(height: 10),
          _buildMapingWebButton(context),
          
        ],
      ),
    );
  }

  Widget _buildProductImage() {
    return Center(
      child: Container(
        width: 200,
        height: 200,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: primaryColor.withOpacity(0.1),
              blurRadius: 10,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(12),
          child: Image.network(
            "http://$config_URL$show_img_on_server${allOrderData['img'] ?? ''}",
            fit: BoxFit.cover,
            errorBuilder: (_, __, ___) => Container(
              color: lightBackground,
              child: Icon(Icons.image_not_supported, size: 50, color: secondaryColor),
            ),
            loadingBuilder: (_, child, progress) {
              return progress == null
                  ? child
                  : Center(child: CircularProgressIndicator(color: secondaryColor));
            },
          ),
        ),
      ),
    );
  }

  Widget _buildDetailsCard() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              allOrderData['product_title'] ?? 'No Title',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: primaryColor),
            ),
            const SizedBox(height: 12),
            _buildDetailRow('Order Date', _formatDate(allOrderData['sent_datetime'] ?? '')),
            if(globals.current_user_role_account != 3)_buildDetailRow('Buyer', '${allOrderData['buyer'] ?? 0}'),
          ],
        ),
      ),
    );
  }

  Widget _buildStatusSection() {
    final status = allOrderData['client_response'] ?? 'Pending';
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(_getStatusIcon(status), color: _getStatusColor(status), size: 30),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Status', style: TextStyle(fontSize: 14, color: Colors.grey)),
                  Text(status, style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: _getStatusColor(status))),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPriceSection() {
    final price = (allOrderData['price'] ?? 0).toStringAsFixed(2);
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: _buildPriceRow('Cost', '\₪$price', isTotal: true),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(fontSize: 16, color: Colors.grey)),
          Text(value, style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500, color: primaryColor)),
        ],
      ),
    );
  }

  Widget _buildPriceRow(String label, String value, {bool isTotal = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(
            fontSize: isTotal ? 18 : 16,
            fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
            color: isTotal ? statusApproved : primaryColor,
          )),
          Text(value, style: TextStyle(
            fontSize: isTotal ? 20 : 16,
            fontWeight: FontWeight.bold,
            color: isTotal ? statusApproved : primaryColor,
          )),
        ],
      ),
    );
  }

  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute}';
    } catch (_) {
      return dateString;
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Approved':
        return statusApproved;
      case 'Rejected':
        return statusRejected;
      case 'Pending':
      default:
        return statusPending;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'Approved':
        return Icons.check_circle;
      case 'Rejected':
        return Icons.cancel;
      case 'Pending':
      default:
        return Icons.access_time;
    }
  }
  
  
  
  Widget _buildTrackingButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton.icon(
        icon: const Icon(Icons.local_shipping, color: Colors.white),
        label: const Text(
          'Tracking',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold,color: Colors.white),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryColor,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => TrackingPage(
                orderId: allOrderData['order_id'],
              ),
            ),
          );
        },
      ),
    );
  }
  
  Widget _buildRatingButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton.icon(
        icon: const Icon(Icons.star, color: Colors.black),
        label: const Text(
          'Rating',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold,color: Colors.black),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: secondaryColor,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => RatingPage(
                orderId: allOrderData['order_id'],
              ),
            ),
          );
        },
      ),
    );
  }
  
  Widget _buildMapingButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton.icon(
        icon: const Icon(Icons.map, color: Colors.black),
        label: const Text(
          'Maping',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold,color: Colors.black),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: lightBackground,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => MapRoutePage(
                orderId: allOrderData['order_id'],
              ),
            ),
          );
        },
      ),
    );
  }
  Widget _buildMapingWebButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton.icon(
        icon: const Icon(Icons.map, color: Colors.black),
        label: const Text(
          'Maping Web',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold,color: Colors.black),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: lightBackground,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => MapRouteWebPage(
                orderId: allOrderData['order_id'],
              ),
            ),
          );
        },
      ),
    );
  }

}

