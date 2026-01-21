import 'package:flutter/material.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/public/globals.dart' as globals;

//import 'package:platform_stores/sent_order/show_sent_order.dart';
//import 'package:platform_stores/received_order/show_received_order.dart';
import 'package:platform_stores/all_order/show_all_order.dart';
import 'package:platform_stores/products/list_shopping_products.dart';

import 'package:platform_stores/products/all_products.dart';
import 'package:platform_stores/products/new_product.dart';
import 'package:platform_stores/users/all_users.dart';

import 'package:hexcolor/hexcolor.dart';

/// الألوان الرئيسية
final Color primaryColor = HexColor("#011432"); // أزرق داكن
final Color secondaryColor = HexColor("#e75423"); // برتقالي أحمر
final Color lightBackground = HexColor("#f8f8f8"); // خلفية فاتحة

/// ------------------------------------------------------------
///                        HOME PAGE
/// ------------------------------------------------------------
class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      body: MainContentScreen(),
      bottomNavigationBar: footer(context),
    );
  }
}

/// ------------------------------------------------------------
///         MAIN SCREEN (Select layout by user role)
/// ------------------------------------------------------------
class MainContentScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    switch (globals.current_user_role_account) {
      case 1:
        return HomeLayoutAdmin();
      case 2:
        return HomeLayoutSeller();
      case 3:
        return HomeLayoutCustomer();
      case 4:
        return HomeLayoutCourier();
      default:
        return Center(child: Text("Unknown Role", style: TextStyle(color: primaryColor)));
    }
  }
}

/// ------------------------------------------------------------
///                    ADMIN LAYOUT (Role 1)
/// ------------------------------------------------------------
class HomeLayoutAdmin extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [lightBackground, Colors.white, lightBackground],
        ),
      ),
      padding: EdgeInsets.all(16),
      child: ListView(
        children: [
          Text(
            "Admin Dashboard",
            style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: primaryColor),
          ),
          SizedBox(height: 20),

          _adminTile(
            context,
            label: "All Orders",
            icon: Icons.shopping_bag,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => ShowAllOrderPage()));
            },
          ),

          _adminTile(
            context,
            label: "All Products",
            icon: Icons.local_offer,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => AllProductsPage()));
            },
          ),

          _adminTile(
            context,
            label: "User Management",
            icon: Icons.people,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => AllUsersPage()));
            },
          ),
        ],
      ),
    );
  }

  Widget _adminTile(BuildContext context,
      {required String label, required IconData icon, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.symmetric(vertical: 10),
        padding: EdgeInsets.all(18),
        decoration: BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.circular(14),
        ),
        child: Row(
          children: [
            Icon(icon, color: secondaryColor, size: 36),
            SizedBox(width: 18),
            Text(label, style: TextStyle(color: Colors.white, fontSize: 20)),
          ],
        ),
      ),
    );
  }
}

/// ------------------------------------------------------------
///                    SELLER LAYOUT (Role 2)
/// ------------------------------------------------------------
class HomeLayoutSeller extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [lightBackground, Colors.white],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      padding: EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Welcome Seller 👨‍💼",
            style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: primaryColor),
          ),
          SizedBox(height: 20),
          Expanded(
            child: GridView.count(
              crossAxisCount: 2,
              mainAxisSpacing: 14,
              crossAxisSpacing: 14,
              children: [
                _sellerCard(
                  label: "Add New Product",
                  icon: Icons.post_add,
                  onTap: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => NewProduct()));
                  },
                ),
                _sellerCard(
                  label: "My Products",
                  icon: Icons.local_offer,
                  onTap: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => AllProductsPage()));
                  },
                ),
                _sellerCard(
                  label: "Received Order",
                  icon: Icons.shopping_basket,
                  onTap: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => ShowAllOrderPage()));
                  },
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _sellerCard({required String label, required IconData icon, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.circular(20),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: secondaryColor, size: 40),
            SizedBox(height: 12),
            Text(label,
                style: TextStyle(color: Colors.white, fontSize: 17, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}

/// ------------------------------------------------------------
///                    CUSTOMER LAYOUT (Role 3)
/// ------------------------------------------------------------
class HomeLayoutCustomer extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [lightBackground, Colors.white],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      padding: EdgeInsets.all(20),
      child: ListView(
        children: [
          Center(
            child: Text(
              "Welcome 👋\nEnjoy Your Shopping",
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: primaryColor),
            ),
          ),
          SizedBox(height: 30),
          _customerButton(
            label: "Shopping",
            icon: Icons.store_mall_directory,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => List_shopping_products()));
            },
          ),
          _customerButton(
            label: "Sent Order",
            icon: Icons.shopping_cart,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => ShowAllOrderPage()));
            },
          ),
        ],
      ),
    );
  }

  Widget _customerButton({required String label, required IconData icon, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.symmetric(vertical: 14),
        padding: EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.circular(18),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: secondaryColor, size: 35),
            SizedBox(width: 14),
            Text(label,
                style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}

/// ------------------------------------------------------------
///                    COURIER LAYOUT (Role 4)
/// ------------------------------------------------------------
class HomeLayoutCourier extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [lightBackground, Colors.white],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      padding: EdgeInsets.all(20),
      child: ListView(
        children: [
          Center(
            child: Text(
              "Welcome Courier 👋\n in your Account",
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: primaryColor),
            ),
          ),
          SizedBox(height: 30),
          _courierButton(
            label: "Courier Order",
            icon: Icons.shopping_cart,
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => ShowAllOrderPage()));
            },
          ),
        ],
      ),
    );
  }

  Widget _courierButton({required String label, required IconData icon, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.symmetric(vertical: 14),
        padding: EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.circular(18),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: secondaryColor, size: 35),
            SizedBox(width: 14),
            Text(label,
                style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}

