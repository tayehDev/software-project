import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/profile/edit_profile.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';

/// 🎨 Colors
const Color primaryColor = Color(0xFF011432);
const Color secondaryColor = Color(0xFFE75423);

Future<Map<String, dynamic>> fetchData(int userId) async {
  Uri uri = useHttps
      ? Uri.https(config_URL, config_unencodedPath)
      : Uri.http(config_URL, config_unencodedPath);

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json; charset=UTF-8'},
    body: jsonEncode({
      "action": "show_profile",
      "by_user_id": userId
    }),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body)['dataView'];
    return Map<String, dynamic>.from(data);
  } else {
    throw Exception('Failed to fetch data.');
  }
}

class ShowProfile extends StatefulWidget {
  final int userId;

  ShowProfile(this.userId);

  @override
  _ShowProfileState createState() => _ShowProfileState(userId);
}

class _ShowProfileState extends State<ShowProfile> {
  final int userId;
  Map<String, dynamic> _dataViewFetched = {};

  _ShowProfileState(this.userId);

  @override
  void initState() {
    super.initState();
    fetchData(userId).then((result) {
      setState(() {
        _dataViewFetched = result;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    final double _width = MediaQuery.of(context).size.width;
    final double _height = MediaQuery.of(context).size.height;

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: topBar(context),
      bottomNavigationBar: footer(context),
      body: SingleChildScrollView(
        child: Column(
          children: [
            SizedBox(height: _height / 15),

            /// 👤 Avatar
            CircleAvatar(
              radius: 60,
              backgroundColor: secondaryColor.withOpacity(0.15),
              backgroundImage: _dataViewFetched.isNotEmpty &&
                      _dataViewFetched['img'] != null
                  ? NetworkImage(
                      "http://$config_URL$show_img_on_server${_dataViewFetched['img']}")
                  : null,
              child: _dataViewFetched.isEmpty
                  ? CircularProgressIndicator(color: secondaryColor)
                  : null,
            ),

            SizedBox(height: 16),

            /// 👤 Name
            Text(
              _dataViewFetched.isNotEmpty
                  ? "${_dataViewFetched['fullname']}"
                  : "Loading...",
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 22,
                color: primaryColor,
                fontWeight: FontWeight.bold,
              ),
            ),

            SizedBox(height: 32),

            /// 📦 Info Card
            Container(
              margin: EdgeInsets.symmetric(horizontal: 20),
              padding: EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: primaryColor.withOpacity(0.08),
                    blurRadius: 12,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Column(
                children: [
                  headerChild("User Info", ""),
                  Divider(color: secondaryColor.withOpacity(0.4)),

                  infoChild(
                    _width,
                    Icons.flag,
                    "${_dataViewFetched['business_name'] ?? ''}",
                  ),
                  infoChild(
                    _width,
                    Icons.phone,
                    _dataViewFetched['phone'] ?? "N/A",
                  ),
                  infoChild(
                    _width,
                    Icons.location_on,
                    "${_dataViewFetched['area_name'] ?? 'N/A'}",
                  ),
                  infoChild(
                    _width,
                    Icons.location_city,
                    _dataViewFetched['address'] ?? "N/A",
                  ),
                ],
              ),
            ),

            /// ✏️ Edit Button
            if (userId == globals.current_user_id)
              Padding(
                padding: const EdgeInsets.only(top: 28),
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: secondaryColor,
                    elevation: 4,
                    padding: EdgeInsets.symmetric(horizontal: 36, vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(14),
                    ),
                  ),
                  onPressed: () {
                    Navigator.pop(context);
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => EditProfilePage(),
                      ),
                    );
                  },
                  child: Text(
                    translate.getTxt('edit')?? 'Edit',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),

            SizedBox(height: 30),
          ],
        ),
      ),
    );
  }

  Widget headerChild(String header, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          header,
          style: TextStyle(
            fontSize: 16,
            color: primaryColor,
            fontWeight: FontWeight.bold,
          ),
        ),
        Text(
          value,
          style: TextStyle(
            fontSize: 16,
            color: secondaryColor,
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }

  Widget infoChild(double width, IconData icon, String data) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 10),
      child: Row(
        children: [
          Icon(icon, size: 26, color: secondaryColor),
          SizedBox(width: 14),
          Expanded(
            child: Text(
              data,
              style: TextStyle(
                fontSize: 15.5,
                color: primaryColor,
              ),
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }
}

