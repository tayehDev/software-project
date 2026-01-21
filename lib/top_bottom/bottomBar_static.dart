import 'dart:io';

import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/home/home_page.dart';

import 'package:platform_stores/products/all_products.dart';
import 'package:platform_stores/all_order/show_all_order.dart';
import 'package:platform_stores/users/all_users.dart';

//import 'package:platform_stores/received_order/show_received_order.dart';


import 'package:platform_stores/products/list_shopping_products.dart';
//import 'package:platform_stores/sent_order/show_sent_order.dart';



/// =======================
/// 🎨 App Color Palette
/// =======================
final Color primaryColor = HexColor("#011432");     // Dark Blue
final Color secondaryColor = HexColor("#e75423");   // Accent Orange
final Color lightBackground = HexColor("#f8f8f8");  // Soft Background

int selectedIndex = 0;

/// =======================
/// 🔻 Footer Widget
/// =======================
Widget footer(BuildContext context) {
  return Container(
    decoration: BoxDecoration(
      color: Colors.white,
      border: Border(
        top: BorderSide(
          color: primaryColor.withOpacity(0.5),
          width: 1,
        ),
      ),
      boxShadow: [
        BoxShadow(
          color: primaryColor.withOpacity(0.5),
          blurRadius: 12,
          offset: const Offset(0, -2),
        ),
      ],
    ),
    child: BottomNavigationBar(
      type: BottomNavigationBarType.fixed,
      backgroundColor: Colors.transparent,
      elevation: 0,

      /// 🎯 Color Distribution
      selectedItemColor: secondaryColor,
      unselectedItemColor: primaryColor.withOpacity(0.99),

      selectedLabelStyle: const TextStyle(
        fontWeight: FontWeight.w600,
        fontSize: 12,
      ),
      unselectedLabelStyle: const TextStyle(
        fontWeight: FontWeight.w400,
        fontSize: 11,
      ),

      items: _buildNavItems(),
      currentIndex: selectedIndex,
      onTap: (index) => _onItemTapped(context, index),
    ),
  );
}

/// =======================
/// 🧱 Navigation Items
/// =======================
List<BottomNavigationBarItem> _buildNavItems() {
  switch (globals.current_user_role_account) {
    case 1: // Admin
      return const [

        BottomNavigationBarItem(
          icon: Icon(Icons.home),
          label: 'Home',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.person_add),
          label: 'Users',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.local_offer),
          label: 'Product',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.shopping_cart),
          label: 'Orders',
        ),
      ];

    case 2: // Seller
      return const [
        BottomNavigationBarItem(
          icon: Icon(Icons.home),
          label: 'Home',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.local_offer),
          label: 'My Products',
        ),
        
        BottomNavigationBarItem(
          icon: Icon(Icons.shopping_cart),
          label: 'Orders',
        ),
      ];

    case 3: // Buyer
      return const [
        BottomNavigationBarItem(
          icon: Icon(Icons.home),
          label: 'Home',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.store_mall_directory),
          label: 'Shoping',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.shopping_cart),
          label: 'Orders',
        ),
      ];
    case 4: // Courier
    default:
      return const [
        BottomNavigationBarItem(
          icon: Icon(Icons.home),
          label: 'Home',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.directions_car),
          label: 'Orders',
        ),
      ];
  }
}

/// =======================
/// 🧭 Navigation Logic
/// =======================
void _onItemTapped(BuildContext context, int index) {
  selectedIndex = index;

  switch (globals.current_user_role_account) {
    case 1:
      _adminNavigation(context, index);
      break;
    case 2:
      _sellerNavigation(context, index);
      break;
    case 3:
      _buyerNavigation(context, index);
      break;
    case 4:
      _courierNavigation(context, index);
      break;
  }
}

/// =======================
/// 👮 Admin Navigation
/// =======================
void _adminNavigation(BuildContext context, int index) {
  switch (index) {
    case 0:
      _goTo(context, HomePage());
      break;
    case 1:
      _goTo(context, AllUsersPage());
      break;
    case 2:
      _goTo(context, AllProductsPage());
      break;
    case 3:
      _goTo(context, ShowAllOrderPage());
      break;
  }
}

/// =======================
/// 🛒 Seller Navigation
/// =======================
void _sellerNavigation(BuildContext context, int index) {
  switch (index) {
    case 0:
      _goTo(context, HomePage());
      break;
    case 1:
      _goTo(context, AllProductsPage());
      break;
    case 2:
      _goTo(context, ShowAllOrderPage());
      break;
  }
}

/// =======================
/// 🧑 Buyer Navigation
/// =======================
void _buyerNavigation(BuildContext context, int index) {
  switch (index) {
    case 0:
      _goTo(context, HomePage());
      break;
    case 1:
      _goTo(context, List_shopping_products());
      break;
    case 2:
      _goTo(context, ShowAllOrderPage());
      break;
  }
}

/// =======================
/// 🧑 Courier Navigation
/// =======================
void _courierNavigation(BuildContext context, int index) {
  switch (index) {
    case 0:
      _goTo(context, HomePage());
      break;
    case 1:
      _goTo(context, ShowAllOrderPage());
      break;
  }
}

/// =======================
/// 🔁 Navigation Helper
/// =======================
void _goTo(BuildContext context, Widget page) {
  Navigator.push(
    context,
    MaterialPageRoute(builder: (_) => page),
  );
}

