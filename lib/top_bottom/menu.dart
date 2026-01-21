import 'dart:convert';
import 'dart:typed_data';
import 'dart:ui';
import 'dart:io'; //for use exit(0)
import 'package:flutter/material.dart';

import 'package:platform_stores/login/login_page.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;
import 'package:platform_stores/home/home_page.dart';
import 'package:platform_stores/profile/show_profile.dart';
import 'package:platform_stores/top_bottom/notification.dart';
import 'package:platform_stores/all_order/show_all_order.dart';
//import 'package:platform_stores/sent_order/show_sent_order.dart';
//import 'package:platform_stores/received_order/show_received_order.dart';
import 'package:platform_stores/users/all_users.dart';
import 'package:hexcolor/hexcolor.dart';

/// ألوان التطبيق
final Color primaryColor = HexColor("#011432");
final Color secondaryColor = HexColor("#e75423");
final Color backgroundGradientStart = HexColor("#ffffff");
final Color backgroundGradientEnd = HexColor("#f0f0f0");

class MenuScreen extends StatefulWidget {
  @override
  _MenuScreen createState() => _MenuScreen();
}

class _MenuScreen extends State<MenuScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      body: MenuScreenBody(),
      bottomNavigationBar: footer(context),
    );
  }
}

class MenuScreenBody extends StatelessWidget {
  Widget buildMenuItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return ListTile(
      leading: Icon(icon, color: primaryColor),
      title: Text(
        title,
        style: TextStyle(color: primaryColor, fontWeight: FontWeight.w600),
      ),
      onTap: onTap,
    );
  }

  @override
  Widget build(BuildContext context) {
    int? role = globals.current_user_role_account;

    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [backgroundGradientStart, backgroundGradientEnd],
        ),
      ),
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              color: primaryColor,
            ),
            child: Text(
              'Menu',
              style: TextStyle(
                color: Colors.white,
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),

          // ==================== ROLE 1 — ADMIN ====================
          if (role == 1) ...[
            buildMenuItem(
              icon: Icons.home,
              title: 'Home',
              onTap: () => Navigator.push(
                  context, MaterialPageRoute(builder: (context) => HomePage())),
            ),
            buildMenuItem(
              icon: Icons.reorder,
              title: 'All Orders',
              onTap: () => Navigator.push(
                  context, MaterialPageRoute(builder: (context) => ShowAllOrderPage())),
            ),
            buildMenuItem(
              icon: Icons.people,
              title: 'Users Management',
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => AllUsersPage()),
              ),
            ),
            buildMenuItem(
              icon: Icons.account_circle,
              title: 'My Account',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) =>
                          ShowProfile(globals.current_user_id!))),
            ),
          ]

          // ==================== ROLE 2 — SELLER ====================
          else if (role == 2) ...[
            buildMenuItem(
              icon: Icons.home,
              title: 'Home',
              onTap: () => Navigator.push(
                  context, MaterialPageRoute(builder: (context) => HomePage())),
            ),
            buildMenuItem(
              icon: Icons.shopping_cart_checkout,
              title: 'Received Orders',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => ShowAllOrderPage())),
            ),
            buildMenuItem(
              icon: Icons.account_circle,
              title: 'My Account',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) =>
                          ShowProfile(globals.current_user_id!))),
            ),
          ]

          // ==================== ROLE 3 — CUSTOMER ====================
          else if (role == 3) ...[
            buildMenuItem(
              icon: Icons.home,
              title: 'Home',
              onTap: () => Navigator.push(
                  context, MaterialPageRoute(builder: (context) => HomePage())),
            ),
            buildMenuItem(
              icon: Icons.shopping_bag,
              title: 'Sent Orders',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => ShowAllOrderPage())),
            ),
            buildMenuItem(
              icon: Icons.account_circle,
              title: 'My Account',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) =>
                          ShowProfile(globals.current_user_id!))),
            ),
          ]
          // ==================== ROLE 4 — COURIER ====================
          else if (role == 4) ...[
            buildMenuItem(
              icon: Icons.home,
              title: 'Home',
              onTap: () => Navigator.push(
                  context, MaterialPageRoute(builder: (context) => HomePage())),
            ),
            buildMenuItem(
              icon: Icons.shopping_bag,
              title: 'Orders',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => ShowAllOrderPage())),
            ),
            buildMenuItem(
              icon: Icons.account_circle,
              title: 'My Account',
              onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) =>
                          ShowProfile(globals.current_user_id!))),
            ),
          ]

          // ==================== ROLE UNKNOWN ====================
          else ...[
            Center(
              child: Padding(
                padding: const EdgeInsets.all(18.0),
                child: Text(
                  "Unknown Role",
                  style: TextStyle(color: secondaryColor, fontWeight: FontWeight.bold),
                ),
              ),
            )
          ],

          // ==================== LOGOUT (مشترك) ====================
          ListTile(
            leading: Icon(Icons.logout, color: secondaryColor),
            title: Text(
              'Logout',
              style: TextStyle(color: secondaryColor, fontWeight: FontWeight.bold),
            ),
            onTap: () {
              globals.current_user_id = null;
              globals.current_user_role_account = null;

              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => LoginPage()),
              );
            },
          ),
        ],
      ),
    );
  }
}

