import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:snippet_coder_utils/FormHelper.dart';
import 'package:http/http.dart' as http;

import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/api/api_backend_method.dart';
//import 'package:platform_stores/sent_order/show_sent_order.dart';
import 'package:platform_stores/all_order/show_all_order.dart';
import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';

/// 🎨 COLORS
final Color primaryColor = HexColor("#011432");
final Color secondaryColor = HexColor("#e75423");
final Color lightBackground = HexColor("#f2f4f8");

class NewPurchase extends StatefulWidget {
  final product_info;

  NewPurchase({required this.product_info});

  @override
  State<NewPurchase> createState() =>
      _NewPurchase(product_info: this.product_info);
}

class _NewPurchase extends State<NewPurchase> {
  final product_info;
  _NewPurchase({required this.product_info});

  final globleFormKey = GlobalKey<FormState>();
  final TextEditingController _c_amount = TextEditingController();

  bool circular = false;
  bool validate = false;
  String? errorText;

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Stack(
        children: [
          // 🔹 الخلفية تغطي كل الصفحة
          Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [lightBackground, Colors.white, lightBackground],
              ),
            ),
          ),

          // 🔹 المحتوى فوق الخلفية
          Scaffold(
            backgroundColor: Colors.transparent,
            appBar: topBar(context),
            bottomNavigationBar: footer(context),
            body: Form(
              key: globleFormKey,
              child: SingleChildScrollView(
                padding: EdgeInsets.symmetric(horizontal: 20, vertical: 20),
                child: Column(
                  children: [
                    /// 🖼️ PRODUCT CARD
                    Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black12,
                            blurRadius: 12,
                            offset: Offset(0, 6),
                          ),
                        ],
                      ),
                      child: Column(
                        children: [
                          ClipRRect(
                            borderRadius: BorderRadius.circular(15),
                            child: product_info["img"] != null &&
                                    product_info["img"].toString().isNotEmpty
                                ? Image.network(
                                    "http://$config_URL$show_img_on_server${product_info["img"]}",
                                    height: 180,
                                    fit: BoxFit.cover,
                                    errorBuilder: (_, __, ___) => Icon(
                                      Icons.image_not_supported,
                                      size: 80,
                                      color: secondaryColor,
                                    ),
                                  )
                                : Icon(
                                    Icons.image_not_supported,
                                    size: 80,
                                    color: secondaryColor,
                                  ),
                          ),
                          SizedBox(height: 15),
                          Text(
                            product_info["product_title"],
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.bold,
                              color: primaryColor,
                            ),
                          ),
                        ],
                      ),
                    ),

                    SizedBox(height: 30),

                    /// 🔢 AMOUNT LABEL
                    Align(
                      alignment: Alignment.centerLeft,
                      child: Text(
                        "Amount",
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: primaryColor,
                        ),
                      ),
                    ),

                    SizedBox(height: 10),

                    /// 🔢 AMOUNT FIELD
                    TextFormField(
                      controller: _c_amount,
                      keyboardType: TextInputType.number,
                      textAlign: TextAlign.center,
                      style: TextStyle(color: primaryColor, fontSize: 16),
                      validator: (value) {
                        if (value!.isEmpty) return "Amount can't be empty";
                        return null;
                      },
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: Colors.white,
                        hintText: "Enter amount",
                        hintStyle: TextStyle(color: Colors.grey),
                        prefixIcon:
                            Icon(Icons.shopping_cart, color: secondaryColor),
                        contentPadding: EdgeInsets.symmetric(vertical: 18),
                        enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: primaryColor, width: 1.5),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor, width: 2),
                        ),
                        errorBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: Colors.red),
                        ),
                      ),
                    ),

                    SizedBox(height: 30),

                    /// 🛒 BUTTON
                    circular
                        ? CircularProgressIndicator(color: secondaryColor)
                        : SizedBox(
                            width: double.infinity,
                            height: 55,
                            child: ElevatedButton(
                              style: ElevatedButton.styleFrom(
                                backgroundColor: secondaryColor,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(15),
                                ),
                                elevation: 6,
                              ),
                              onPressed: () async {
                                if (_c_amount.text.isEmpty) {
                                  setState(() {
                                    validate = false;
                                    errorText = "Amount can't be empty";
                                  });
                                  return;
                                }

                                setState(() => circular = true);

                                Map<String, dynamic> response =
                                    await api_backend_post_request({
                                  "action": "new_purchases",
                                  "amount": _c_amount.text,
                                  "product_id": product_info["product_id"],
                                  "by_user_id": globals.current_user_id,
                                });

                                setState(() => circular = false);

                                if (response["status"] == "succeed") {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (context) =>
                                            ShowAllOrderPage()),
                                  );
                                } else if (response["status"] == "failed") {
                                  alert_msg(
                                      context, "تنبيه", response["contentMsg"]);
                                } else {
                                  alert_msg(context, "خطأ", "غير معروف");
                                }
                              },
                              child: Text(
                                "Purchase Now",
                                style: TextStyle(
                                  fontSize: 18,
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),

                    SizedBox(height: 40),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

