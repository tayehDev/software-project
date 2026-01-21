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
import 'package:platform_stores/all_order/show_one_row_allOrders.dart';

/// =======================
/// 🎨 Colors
/// =======================
final Color primaryColor = HexColor("#011432"); // Dark blue
final Color secondaryColor = HexColor("#e75423"); // Orange-red
final Color lightBackground = HexColor("#f8f8f8");

class ShowAllOrderPage extends StatefulWidget {
  @override
  State<ShowAllOrderPage> createState() => _ShowAllOrderPageState();
}

class _ShowAllOrderPageState extends State<ShowAllOrderPage> {
  String selectedStatusFilter = 'All'; // All, Pending, Approved, Rejected

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: topBar(context),
        bottomNavigationBar: footer(context),
        body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [lightBackground, Colors.white, lightBackground],
          ),
        ),
        child:Column(
          children: [
            const SizedBox(height: 10),
            _buildStatusFilter(), // ✅ فلتر الحالة
            Expanded(child: _buildFutureBuilder()),
          ],
        ),
        ),
      ),
    );
  }

  Widget _buildStatusFilter() {
    final List<String> statuses = ['All', 'Pending', 'Approved', 'Rejected'];

    if (globals.current_user_role_account != 4)
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: statuses.map((status) {
          final bool isSelected = status == selectedStatusFilter;
          return GestureDetector(
            onTap: () {
              setState(() {
                selectedStatusFilter = status;
              });
            },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: isSelected ? secondaryColor : lightBackground,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(
                  color: isSelected ? secondaryColor : primaryColor.withOpacity(0.3),
                ),
              ),
              child: Text(
                status,
                style: TextStyle(
                  color: isSelected ? Colors.white : primaryColor,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
    else
    return SizedBox(height: 10);//empty

  }

  FutureBuilder<List<Map<String, dynamic>>> _buildFutureBuilder() {
    return FutureBuilder<List<Map<String, dynamic>>>(
      future: _fetchOrders(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }
        if (snapshot.hasError) {
          return Center(
            child: Text('Error: ${snapshot.error}', style: TextStyle(color: secondaryColor)),
          );
        }
        if (!snapshot.hasData || snapshot.data!.isEmpty) {
          return const Center(child: Text('No orders available'));
        }

        // ✅ Apply status filter
        final filteredOrders = snapshot.data!.where((order) {
          if (selectedStatusFilter == 'All') return true;
          return (order['client_response'] ?? 'Pending') == selectedStatusFilter;
        }).toList();

        if (filteredOrders.isEmpty) {
          return const Center(child: Text('No orders for selected status'));
        }

        return ListView.builder(
          itemCount: filteredOrders.length,
          itemBuilder: (context, index) {
            final order = filteredOrders[index];
            return _buildOrderItem(order);
          },
        );
      },
    );
  }

  Widget _buildOrderItem(Map<String, dynamic> order) {
    final status = order['client_response'] ?? 'Pending';
    final price = (order['price'] ?? 0).toStringAsFixed(2);

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border(
          left: BorderSide(color: secondaryColor, width: 5),
        ),
        boxShadow: [
          BoxShadow(
            color: primaryColor.withOpacity(0.06),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: ListTile(
        leading: ClipOval(
          child: Image.network(
            "http://$config_URL$show_img_on_server${order['img'] ?? ''}",
            width: 60,
            height: 60,
            fit: BoxFit.cover,
            errorBuilder: (_, __, ___) => Container(
              color: lightBackground,
              child: Icon(Icons.image_not_supported, color: secondaryColor),
            ),
          ),
        ),
        title: Text(
          order['product_title'] ?? 'No Title',
          style: TextStyle(fontWeight: FontWeight.bold, color: primaryColor),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Status: $status',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: _getStatusColor(status),
              ),
            ),
            Text(
              'Price: \₪$price',
              style: TextStyle(color: primaryColor),
            ),
            Text(
              'Date: ${order['sent_datetime'] ?? ''}',
              style: TextStyle(color: primaryColor.withOpacity(0.7)),
            ),
          ],
        ),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => ShowOneRowAllOrder(order['order_id'] ?? 0),
            ),
          );
        },
      ),
    );
  }

  Future<List<Map<String, dynamic>>> _fetchOrders() async {
    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    final response = await http.post(
      uri,
      headers: {'Content-Type': 'application/json; charset=UTF-8'},
      body: jsonEncode({
        "action": "show_all_order",
        "by_user_id": globals.current_user_id
      }),
    );

    if (response.statusCode == 200) {
      List<dynamic> dataList = jsonDecode(response.body)['dataView'];
      return List<Map<String, dynamic>>.from(dataList);
    } else {
      throw Exception('Failed to load all orders.');
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Approved':
        return Colors.green;
      case 'Rejected':
        return Colors.red;
      case 'Pending':
      default:
        return Colors.orange;
    }
  }
}

