import 'dart:convert';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:platform_stores/login/login_page.dart';
import 'package:snippet_coder_utils/FormHelper.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/profile/edit_profile.dart';

import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;

class RegisterPage extends StatefulWidget {
  const RegisterPage({Key? key}) : super(key: key);

  @override
  _RegisterPage createState() => _RegisterPage();
}

class _RegisterPage extends State<RegisterPage> {

  bool hidepassWord = true;
  bool hiderepassWord = true;

  final globleFormKey = GlobalKey<FormState>();

  TextEditingController _c_fullname = TextEditingController();
  TextEditingController _c_phone = TextEditingController();
  TextEditingController _c_password = TextEditingController();
  TextEditingController _c_repassword = TextEditingController();

  bool validate = false;
  bool circular = false;
  String? errorText;

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
              child: _registerUI(context), // أو _forgotPasswordUI
            ),
          ],
        ),
      ),
    );

  }

  Widget _registerUI(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        children: [

          /// IMAGE
          Container(
            height: MediaQuery.of(context).size.height / 3,
            alignment: Alignment.center,
            child: Image.asset(
              "assets/images/signup.png",
              width: 160,
            ),
          ),

          /// TITLE
          Text(
            translate.getTxt("newAccount") ?? "",
            style: TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: primaryColor,
            ),
          ),

          const SizedBox(height: 30),

          /// fullname
          _inputField(
            controller: _c_fullname,
            hint: translate.getTxt("fullname") ?? "Full Name",
            icon: Icons.person,
            validator: (v) =>
                v!.isEmpty ? "fullname can't be empty" : null,
          ),
          /// USERNAME / PHONE
          _inputField(
            controller: _c_phone,
            hint: translate.getTxt("phone") ?? "",
            icon: Icons.person,
            validator: (v) =>
                v!.isEmpty ? "phone can't be empty" : null,
          ),

          /// PASSWORD
          _inputField(
            controller: _c_password,
            hint: translate.getTxt("password") ?? "",
            icon: hidepassWord ? Icons.visibility_off : Icons.visibility,
            obscure: hidepassWord,
            isPassword: true,
          ),

          /// RE PASSWORD
          _inputField(
            controller: _c_repassword,
            hint: translate.getTxt("repassword") ?? "",
            icon: hiderepassWord ? Icons.visibility_off : Icons.visibility,
            obscure: hiderepassWord,
            isRePassword: true,
          ),

          const SizedBox(height: 30),

          /// REGISTER BUTTON
          circular
              ? const CircularProgressIndicator()
              : FormHelper.submitButton(
                  translate.getTxt("newAccount") ?? "",
                  () async {
                    if (_c_phone.text.isEmpty) {
                      validate = false;
                      errorText = "phone Can't be empty";
                      return;
                    }
                    if (_c_fullname.text.isEmpty) {
                      validate = false;
                      errorText = "fullname Can't be empty";
                      return;
                    }

                    if (_c_password.text != _c_repassword.text) {
                      alert_msg(
                        context,
                        "Check Password!",
                        "Your password is not equal",
                      );
                      return;
                    }

                    Map<String, dynamic> response_list =
                        await api_backend_post_request({
                      "action": "do_signup",
                      "fullname": _c_fullname.text,
                      "phone": _c_phone.text,
                      "password": _c_password.text
                    });

                    if (response_list["status"] == "succeed") {
                      globals.current_user_id =
                          response_list["user_id"];
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditProfilePage(),
                        ),
                      );
                    } else if (response_list["status"] == "failed") {
                      alert_msg(
                        context,
                        "تنبيه",
                        response_list["contentMsg"],
                      );
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

          /// BACK TO LOGIN
          RichText(
            text: TextSpan(
              style: const TextStyle(fontSize: 16),
              children: [
                TextSpan(
                  text: translate.getTxt("login") ?? "",
                  style: TextStyle(
                    color: secondaryColor,
                    fontWeight: FontWeight.bold,
                  ),
                  recognizer: TapGestureRecognizer()
                    ..onTap = () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => const LoginPage(),
                        ),
                      );
                    },
                ),
              ],
            ),
          ),

          const SizedBox(height: 30),
        ],
      ),
    );
  }

  /// INPUT FIELD WIDGET
  Widget _inputField({
    required TextEditingController controller,
    required String hint,
    required IconData icon,
    bool obscure = false,
    bool isPassword = false,
    bool isRePassword = false,
    String? Function(String?)? validator,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 25, vertical: 10),
      child: TextFormField(
        controller: controller,
        obscureText: obscure,
        validator: validator,
        style: TextStyle(color: primaryColor),
        textAlign: translate.set_alignment == "right"
            ? TextAlign.right
            : TextAlign.left,
        decoration: InputDecoration(
          filled: true,
          fillColor: primaryColor.withOpacity(0.04),
          hintText: hint,
          hintStyle:
              TextStyle(color: primaryColor.withOpacity(0.6)),
          prefixIcon: IconButton(
            icon: Icon(icon, color: primaryColor),
            onPressed: (isPassword || isRePassword)
                ? () {
                    setState(() {
                      if (isPassword) {
                        hidepassWord = !hidepassWord;
                      }
                      if (isRePassword) {
                        hiderepassWord = !hiderepassWord;
                      }
                    });
                  }
                : null,
          ),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide: BorderSide(color: primaryColor),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide:
                BorderSide(color: primaryColor.withOpacity(0.4)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(18),
            borderSide:
                BorderSide(color: secondaryColor, width: 2),
          ),
        ),
      ),
    );
  }
}

