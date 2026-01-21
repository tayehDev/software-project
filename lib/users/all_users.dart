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
import 'package:platform_stores/users/new_user.dart';

/// =======================
/// 🎨 Color Palette
/// =======================
final Color primaryColor = HexColor("#011432");
final Color secondaryColor = HexColor("#e75423");
final Color lightBackground = HexColor("#f5f6fa");

class AllUsersPage extends StatefulWidget {
  @override
  State<AllUsersPage> createState() => _AllUsersPageState();
}

class _AllUsersPageState extends State<AllUsersPage> {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        backgroundColor: lightBackground,
        appBar: topBar(context),
        bottomNavigationBar: footer(context),
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              child: Text(
                'Show All Users',
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                  color: primaryColor,
                ),
              ),
            ),
            Expanded(child: _buildFuture()),
          ],
        ),
      ),
    );
  }

  Widget _buildFuture() {
    return FutureBuilder<List<Map<String, dynamic>>>(
      future: fetchAllUsers(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return Center(
            child: CircularProgressIndicator(color: secondaryColor),
          );
        }

        if (snapshot.hasError) {
          return Center(
            child: Text(
              "Error loading users",
              style: TextStyle(color: secondaryColor),
            ),
          );
        }

        final users = snapshot.data ?? [];

        if (users.isEmpty) {
          return Center(
            child: Text(
              "No users found",
              style: TextStyle(
                fontSize: 16,
                color: primaryColor.withOpacity(0.7),
              ),
            ),
          );
        }

        return AllUsersList(users: users);
      },
    );
  }
}

/// =======================
/// 📋 Users List
/// =======================
class AllUsersList extends StatefulWidget {
  final List<Map<String, dynamic>> users;

  const AllUsersList({Key? key, required this.users}) : super(key: key);

  @override
  State<AllUsersList> createState() => _AllUsersListState();
}

class _AllUsersListState extends State<AllUsersList> {
  void _confirmDelete(BuildContext context, int userId, int index) async {
    bool? confirm = await showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(
          'Confirm Delete',
          style: TextStyle(color: primaryColor, fontWeight: FontWeight.bold),
        ),
        content: const Text('Do you want to delete this user?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('Cancel', style: TextStyle(color: primaryColor)),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );

    if (confirm == true) {
      var response = await api_backend_post_request({
        "action": "delete_user",
        "user_id": userId,
        "by_user_id": globals.current_user_id,
      });

      if (response["status"] == "succeed") {
        setState(() {
          widget.users.removeAt(index); // ✅ تحديث الجدول مباشرة
        });

        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: secondaryColor,
            content: const Text("Deleted successfully"),
          ),
        );
      } else {
        alert_msg(context, "Error", "Failed to delete user");
      }
    }
  }

  String getRole(int role) {
    switch (role) {
      case 1:
        return "Admin";
      case 2:
        return "Seller";
      case 3:
        return "Customer";
      case 4:
        return "Courier";
      default:
        return "Unknown";
    }
  }

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      padding: const EdgeInsets.only(bottom: 10),
      itemCount: widget.users.length,
      itemBuilder: (_, index) {
        final u = widget.users[index];

        final img = u['img']?.toString() ?? '';
        final fullname = u['fullname']?.toString() ?? '';
        final businessName = u['business_name']?.toString() ?? '';
        final phone = u['phone']?.toString() ?? '';
        final role = int.tryParse(u['user_role_account'].toString()) ?? 3;

        return Container(
          margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(14),
            boxShadow: [
              BoxShadow(
                color: primaryColor.withOpacity(0.08),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: ListTile(
            leading: CircleAvatar(
              radius: 26,
              backgroundColor: secondaryColor.withOpacity(0.15),
              backgroundImage: img.isEmpty
                ? const AssetImage('assets/no_user.png') as ImageProvider
                : NetworkImage(
                    "http://$config_URL$show_img_on_server$img",
                  ),

            ),
            title: Text(
              fullname.isEmpty ? businessName : fullname,
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: primaryColor,
              ),
            ),
            subtitle: Text(
              "Role: ${getRole(role)}\nPhone: $phone",
              style: TextStyle(
                fontSize: 13,
                color: primaryColor.withOpacity(0.7),
              ),
            ),
            trailing: IconButton(
              icon: const Icon(Icons.delete, color: Colors.red),
              onPressed: () => _confirmDelete(context, u['id'], index),
            ),
            onTap: () => Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) => NewUser(row_id: u['id'].toString()),
              ),
            ),
          ),
        );
      },
    );
  }
}

/// =======================
/// 🌐 API
/// =======================
Future<List<Map<String, dynamic>>> fetchAllUsers() async {
  final Uri uri = useHttps
      ? Uri.https(config_URL, config_unencodedPath)
      : Uri.http(config_URL, config_unencodedPath);

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json; charset=UTF-8'},
    body: jsonEncode({
      "action": "show_all_users",
      "by_user_id": globals.current_user_id,
    }),
  );

  if (response.statusCode == 200) {
    final json = jsonDecode(response.body);
    final list = json["dataView"]["user_info"] as List;
    return list.map((e) => e as Map<String, dynamic>).toList();
  } else {
    throw Exception("Failed to load users");
  }
}

