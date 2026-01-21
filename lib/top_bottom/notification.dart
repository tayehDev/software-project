import 'dart:convert';
import 'dart:typed_data';
import 'dart:ui';
import 'dart:io'; //for use exit(0)
import 'package:flutter/material.dart';

import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';

import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;

/// ألوان التطبيق
final Color primaryColor = Color(0xFF011432); // رئيسية
final Color secondaryColor = Color(0xFFE75423); // ثانوية
final Color backgroundGradientStart = Color(0xFFFFFFFF);
final Color backgroundGradientEnd = Color(0xFFF0F0F0);

class NotificationScreen extends StatefulWidget {
  @override
  _NotificationScreen createState() => _NotificationScreen();
}

class _NotificationScreen extends State<NotificationScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      body: NotificationBody(),
      bottomNavigationBar: footer(context),
    );
  }
}

class NotificationBody extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [backgroundGradientStart, backgroundGradientEnd],
        ),
      ),
      child: Center(
        child: Text(
          'Notifications',
          style: TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.bold,
            color: primaryColor,
          ),
        ),
      ),
    );
  }
}

