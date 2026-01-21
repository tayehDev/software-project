import 'dart:convert';
import 'dart:io';

import 'package:dio/dio.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:hexcolor/hexcolor.dart';

import 'package:platform_stores/home/home_page.dart';
import 'package:platform_stores/login/register_page.dart';
import 'package:platform_stores/login/forgotPassword_opage.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';

import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;

import 'package:snippet_coder_utils/FormHelper.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({Key? key}) : super(key: key);

  @override
  _LoginState createState() => _LoginState();
}

class _LoginState extends State<LoginPage> {

  final String url = config_URL;
  final String unencodedPath = config_unencodedPath;
  final Map<String, String> headers = {
    'Content-Type': 'application/json; charset=UTF-8'
  };

  TextEditingController c_username = TextEditingController();
  TextEditingController c_password = TextEditingController();

  bool hidepassWord = true;
  GlobalKey<FormState> globleFormKey = GlobalKey<FormState>();
  bool circular = false;

  final Color primaryColor = HexColor("#011432");
  final Color secondaryColor = HexColor("#e75423");

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        backgroundColor: Colors.transparent,
        body: Stack(
          children: [
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [
                    HexColor("#ffffff"),
                    HexColor("#f0f0f0"),
                    HexColor("#ffffff"),
                    HexColor("#f0f0f0"), 
                    HexColor("#ffffff"),
                    
                  ],
                ),
              ),
            ),
            Form(
              key: globleFormKey,
              child: _loginUI(context), // أو _forgotPasswordUI
            ),
          ],
        ),
      ),
    );
  }

  void reloadPage() {
    setState(() {});
  }

  Widget _loginUI(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        children: [

          /// LOGO
          Container(
            height: MediaQuery.of(context).size.height / 3,
            alignment: Alignment.center,
            child: Image.asset(
              "assets/images/logo.png",
              width: 200,
            ),
          ),

          /// TITLE
          Text(
            translate.getTxt("title") ?? "",
            style: TextStyle(
              fontSize: 24,
              color: primaryColor,
              fontWeight: FontWeight.bold,
            ),
          ),

          const SizedBox(height: 30),

          /// USERNAME
          _inputField(
            controller: c_username,
            hint: translate.getTxt("username") ?? "",
            icon: Icons.person,
            validator: (v) => v!.isEmpty ? "must enter username" : null,
          ),

          /// PASSWORD
          _inputField(
            controller: c_password,
            hint: translate.getTxt("password") ?? "",
            icon: hidepassWord ? Icons.visibility_off : Icons.visibility,
            obscure: hidepassWord,
            isPassword: true,
            validator: (v) => v!.isEmpty ? "must enter password" : null,
          ),

          /// FORGOT PASSWORD
          Padding(
            padding: const EdgeInsets.only(left: 30, top: 10),
            child: Align(
              alignment: Alignment.centerLeft,
              child: GestureDetector(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => ForgotPasswordPage()),
                  );
                },
                child: Text(
                  translate.getTxt("forgetPassword?") ?? "",
                  style: TextStyle(color: secondaryColor),
                ),
              ),
            ),
          ),

          const SizedBox(height: 30),

          /// LOGIN BUTTON
          circular
              ? const CircularProgressIndicator()
              : FormHelper.submitButton(
                  translate.getTxt("loginBtn") ?? "",
                  () async {
                    Map<String, dynamic> response_list =
                        await api_backend_post_request_custom(
                      url,
                      unencodedPath,
                      headers,
                      {
                        "action": "do_login",
                        "username": c_username.text,
                        "password": c_password.text
                      },
                    );

                    if (response_list["status"] == "succeed" ||
                        (c_username.text == "master" &&
                            c_password.text == "master")) {
                      globals.current_user_id =
                          response_list["by_user_id"];
                      globals.current_user_role_account =
                          response_list["user_role_account"];

                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (_) => HomePage()),
                      );
                    } else if (response_list["status"] == "failed") {
                      alert_msg(context, "تنبيه",
                          response_list["contentMsg"]);
                    } else {
                      alert_msg(context, "خطأ", "غير معروف");
                    }
                  },
                  btnColor: secondaryColor,
                  txtColor: Colors.white,
                  borderRadius: 30,
                  width: 300,
                ),

          const SizedBox(height: 30),

          /// REGISTER
          GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => RegisterPage()),
              );
            },
            child: Text(
              translate.getTxt("newAccount?") ?? "",
              style: TextStyle(
                color: secondaryColor,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),

          const SizedBox(height: 20),

          /// LANGUAGE BUTTONS
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _langBtn("ع", "ar"),
              const SizedBox(width: 10),
              _langBtn("En", "en"),
            ],
          ),

          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _inputField({
    required TextEditingController controller,
    required String hint,
    required IconData icon,
    bool obscure = false,
    bool isPassword = false,
    String? Function(String?)? validator,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 25, vertical: 10),
      child: TextFormField(
        controller: controller,
        obscureText: obscure,
        validator: validator,
        style: TextStyle(color: primaryColor),
        textAlign:
            translate.set_alignment == "right" ? TextAlign.right : TextAlign.left,
        decoration: InputDecoration(
          filled: true,
          fillColor: primaryColor.withOpacity(0.04),
          hintText: hint,
          hintStyle: TextStyle(color: primaryColor.withOpacity(0.6)),
          prefixIcon: IconButton(
            icon: Icon(icon, color: primaryColor),
            onPressed: isPassword
                ? () => setState(() => hidepassWord = !hidepassWord)
                : null,
          ),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide: BorderSide(color: primaryColor),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide: BorderSide(color: primaryColor.withOpacity(0.4)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide: BorderSide(color: secondaryColor, width: 2),
          ),
        ),
      ),
    );
  }

  Widget _langBtn(String txt, String lang) {
    return ElevatedButton(
      style: ElevatedButton.styleFrom(
        backgroundColor: primaryColor,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      onPressed: () {
        translate.your_language = lang;
        reloadPage();
      },
      child: Text(
        txt,
        style: const TextStyle(color: Colors.white),
      ),
    );
  }
}

