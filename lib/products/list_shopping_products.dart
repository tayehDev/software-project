import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:hexcolor/hexcolor.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/all_order/new_purchases.dart';

/// =======================
/// 🎨 Colors
/// =======================
final Color primaryColor = HexColor("#011432"); // Dark blue
final Color secondaryColor = HexColor("#e75423"); // Orange-red
final Color lightBackground = HexColor("#f8f8f8");

class List_shopping_products extends StatefulWidget {
  @override
  State<List_shopping_products> createState() => _List_shopping_productsState();
}

class _List_shopping_productsState extends State<List_shopping_products> {
  String searchText = '';
  final TextEditingController _searchController = TextEditingController();
  List<Map<String, dynamic>> products = [];
  bool isLoading = true;

  void _onSearchChanged() {
    setState(() {
      searchText = _searchController.text;
      _loadProducts();
    });
  }

  @override
  void initState() {
    super.initState();
    _searchController.addListener(_onSearchChanged);
    _loadProducts();
  }

  @override
  void dispose() {
    _searchController.removeListener(_onSearchChanged);
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _loadProducts() async {
    setState(() {
      isLoading = true;
    });
    products = await fetch_data_from_api(searchText);
    setState(() {
      isLoading = false;
    });
  }

  void _removeProductFromList(int productId) {
    setState(() {
      products.removeWhere((p) => p['product_id'] == productId);
    });
  }

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
          child: Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(12.0),
                child: TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: "Search products...",
                    prefixIcon: Icon(Icons.search, color: primaryColor),
                    filled: true,
                    fillColor: Colors.white,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
              ),
              Expanded(
                child: isLoading
                    ? Center(child: CircularProgressIndicator(color: secondaryColor))
                    : products.isEmpty
                        ? const Center(
                            child: Text(
                              "No products available",
                              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                            ),
                          )
                        : AllProductsList(
                            products,
                            onDeleted: _removeProductFromList, // إزالة المنتج مباشرة
                          ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class AllProductsList extends StatelessWidget {
  final List<Map<String, dynamic>> products;
  final Function(int) onDeleted;

  AllProductsList(this.products, {required this.onDeleted});


  String _brandCopyLabel(int brandCopy) {
    switch (brandCopy) {
      case 1:
        return "Brand";
      case 2:
        return "Copy";
      default:
        return "";
    }
  }

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: products.length,
      itemBuilder: (context, index) {
        final product = products[index];
        final brandCopyLabel = _brandCopyLabel(product['brand_copy'] ?? 0);

        return Container(
          margin: const EdgeInsets.symmetric(vertical: 6, horizontal: 10),
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [Colors.white, lightBackground],
            ),
            borderRadius: BorderRadius.circular(12),
            boxShadow: [
              BoxShadow(
                color: primaryColor.withOpacity(0.1),
                blurRadius: 6,
                offset: const Offset(0, 3),
              ),
            ],
          ),
          child: ListTile(
            contentPadding: const EdgeInsets.all(10),
            leading: ClipRRect(
              borderRadius: BorderRadius.circular(35),
              child: Image.network(
                "http://$config_URL$show_img_on_server${product['img']}",
                width: 70,
                height: 70,
                fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => Container(
                  color: lightBackground,
                  child: Icon(Icons.image_not_supported,
                      size: 40, color: secondaryColor),
                ),
              ),
            ),
            title: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  product['product_title'],
                  style: TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 17,
                      color: primaryColor),
                ),
                if (product.containsKey('publisher_fullname') &&
                    product['publisher_fullname'] != null)
                  Text(
                    '${product['publisher_fullname']} - ${product['publisher_business']}',
                    style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w500,
                        color: secondaryColor),
                  ),
                if (brandCopyLabel.isNotEmpty)
                  Container(
                    margin: const EdgeInsets.only(top: 4),
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                    decoration: BoxDecoration(
                      color: brandCopyLabel == "Brand" ? Colors.green.shade100 : Colors.orange.shade100,
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      brandCopyLabel,
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: brandCopyLabel == "Brand" ? Colors.green.shade800 : Colors.orange.shade800,
                      ),
                    ),
                  ),
              ],
            ),
            subtitle: Text(
              'Price: \₪${product['price'] ?? '0'}',
              style: TextStyle(fontSize: 15, color: secondaryColor),
            ),
            onTap: () => Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) =>
                    NewPurchase(product_info: product),
              ),
            ),
          ),
        );
      },
    );
  }
}

/// جلب البيانات مع البحث
Future<List<Map<String, dynamic>>> fetch_data_from_api(String searchText) async {
  Uri uri;
  if (useHttps) {
    uri = Uri.https(config_URL, config_unencodedPath);
  } else {
    uri = Uri.http(config_URL, config_unencodedPath);
  }

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json; charset=UTF-8'},
    body: jsonEncode({
      "action": "show_all_products",
      "by_user_id": globals.current_user_id,
      "search_text": searchText
    }),
  );

  if (response.statusCode == 200) {
    var data = jsonDecode(response.body)['dataView']['product_info'] as List;
    return data.map((post) => post as Map<String, dynamic>).toList();
  } else {
    throw Exception('Failed to load products.');
  }
}

