import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:platform_stores/login/login_page.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;
import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/top_bottom/notification.dart';
import 'package:platform_stores/profile/show_profile.dart';
import 'package:platform_stores/products/new_product.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:platform_stores/top_bottom/menu.dart';

/// =======================
/// 🎨 الألوان الرئيسية
/// =======================
final Color primaryColor = HexColor("#011432"); // أزرق داكن
final Color secondaryColor = HexColor("#e75423"); // برتقالي أحمر

/// =======================
/// 🌐 جلب بيانات المستخدم
/// =======================
Future<Map<dynamic, dynamic>> fetchData(int by_user_id) async {
  Uri uri;
  if (useHttps) {
    uri = Uri.https(config_URL, config_unencodedPath);
  } else {
    uri = Uri.http(config_URL, config_unencodedPath);
  }

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json; charset=UTF-8'},
    body: jsonEncode({"action": "show_profile", "by_user_id": by_user_id}),
  );

  if (response.statusCode == 200) {
    return (jsonDecode(response.body)['dataView'] as Map<dynamic, dynamic>);
  } else {
    throw Exception('Failed to fetch profile data.');
  }
}

/// =======================
/// 🏗️ AppBar مخصص
/// =======================
AppBar topBar(BuildContext context) {
  int notificationCount = 5; // مثال

  // الصورة الشخصية على اليسار
  Widget buildProfileSection() {
    return FutureBuilder<Map<dynamic, dynamic>>(
      future: fetchData(globals.current_user_id!),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Padding(
            padding: EdgeInsets.all(8.0),
            child: CircleAvatar(backgroundColor: Colors.grey, radius: 20),
          );
        } else if (snapshot.hasError) {
          return const Padding(
            padding: EdgeInsets.all(8.0),
            child: CircleAvatar(
              backgroundColor: Colors.grey,
              child: Icon(Icons.error, color: Colors.red),
              radius: 20,
            ),
          );
        } else {
          final data = snapshot.data!;
          return GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ShowProfile(globals.current_user_id!),
                ),
              );
            },
            child: Padding(
              padding: const EdgeInsets.all(8.0),
              child: CircleAvatar(
                backgroundImage: data['img'] != null
                    ? NetworkImage(
                        "http://$config_URL$show_img_on_server${data['img']}",
                      )
                    : null,
                backgroundColor: Colors.grey.shade300,
                radius: 20,
              ),
            ),
          );
        }
      },
    );
  }

  // اسم المستخدم في الوسط
  Widget buildTitleSection() {
    return FutureBuilder<Map<dynamic, dynamic>>(
      future: fetchData(globals.current_user_id!),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return Text(
            "Loading...",
            style: TextStyle(color: primaryColor, fontWeight: FontWeight.bold),
          );
        } else if (snapshot.hasError) {
          return Text(
            "Error",
            style: TextStyle(color: secondaryColor, fontWeight: FontWeight.bold),
          );
        } else {
          final data = snapshot.data!;
          return GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ShowProfile(globals.current_user_id!),
                ),
              );
            },
            child: Text(
              "${data['fullname']} ${data['business_name']}",
              style: TextStyle(color: primaryColor, fontWeight: FontWeight.bold),
            ),
          );
        }
      },
    );
  }

  // أيقونة الإشعارات
  Widget notificationIcon() {
    return Stack(
      children: [
        IconButton(
          icon: Icon(Icons.notifications_none, color: primaryColor),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => NotificationScreen()),
            );
          },
        ),
        if (notificationCount > 0)
          Positioned(
            right: 8,
            top: 8,
            child: Container(
              padding: const EdgeInsets.all(2),
              decoration: BoxDecoration(
                color: secondaryColor,
                borderRadius: BorderRadius.circular(6),
              ),
              constraints: const BoxConstraints(minWidth: 14, minHeight: 14),
              child: Text(
                '$notificationCount',
                style: const TextStyle(color: Colors.white, fontSize: 8),
                textAlign: TextAlign.center,
              ),
            ),
          ),
      ],
    );
  }

  // قائمة الأزرار على اليمين
  List<Widget> buildActions() {
    List<Widget> actions = [];

    // زر إضافة أو ماسنجر حسب الدور
    if (globals.current_user_role_account == 1) {
      actions.add(
        IconButton(
          icon: Icon(Icons.messenger, color: primaryColor),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => NotificationScreen()),
            );
          },
        ),
      );
    } else if (globals.current_user_role_account == 2) {
      actions.add(
        IconButton(
          icon: Icon(Icons.add_circle_outline, color: primaryColor),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (context) => NewProduct()),
            );
          },
        ),
      );
    }

    // أيقونة الإشعارات
    actions.add(notificationIcon());

    // زر الـ Menu دائمًا على أقصى اليمين
    actions.add(
      IconButton(
        icon: Icon(Icons.menu, color: primaryColor),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => MenuScreen()),
          );
        },
      ),
    );

    return actions;
  }

  return AppBar(
    backgroundColor: Colors.white,
    elevation: 3,
    bottom: PreferredSize(
      preferredSize: const Size.fromHeight(1.0),
      child: Container(color: primaryColor.withOpacity(0.2), height: 1.0),
    ),
    leading: buildProfileSection(),
    title: buildTitleSection(),
    actions: buildActions(),
  );
}

