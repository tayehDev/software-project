import 'dart:ui';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:platform_stores/login/login_page.dart';
import 'package:snippet_coder_utils/FormHelper.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/translate.dart' as translate;

class ForgotPasswordPage extends StatefulWidget {
  const ForgotPasswordPage({Key? key}) : super(key: key);

  @override
  State<ForgotPasswordPage> createState() => _ForgotPasswordPage();
}

class _ForgotPasswordPage extends State<ForgotPasswordPage> {
  final globleFormKey = GlobalKey<FormState>();
  final TextEditingController _c_phone = TextEditingController();

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
            /// BACKGROUND
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [
                    Colors.white,
                    HexColor("#f0f0f0"),
                    Colors.white,
                  ],
                ),
              ),
            ),

            /// CENTER CONTENT
            Center(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 22),
                child: Column(
                  children: [
                    /// TRANSPARENT CARD / CONTAINER
                    ClipRRect(
                      borderRadius: BorderRadius.circular(26),
                      child: BackdropFilter(
                        filter: ImageFilter.blur(sigmaX: 8, sigmaY: 8),
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.4),
                            borderRadius: BorderRadius.circular(26),
                            border: Border.all(
                              color: primaryColor.withOpacity(0.3),
                              width: 1.5,
                            ),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 24, vertical: 32),
                            child: Form(
                              key: globleFormKey,
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  /// IMAGE
                                  Image.asset(
                                    "assets/images/restore.png",
                                    width: 110,
                                  ),

                                  const SizedBox(height: 20),

                                  /// TITLE
                                  Text(
                                    translate.getTxt("forgetPassword?") ?? "",
                                    style: TextStyle(
                                      fontSize: 22,
                                      fontWeight: FontWeight.bold,
                                      color: primaryColor,
                                    ),
                                  ),

                                  const SizedBox(height: 30),

                                  /// PHONE / USERNAME FIELD
                                  TextFormField(
                                    controller: _c_phone,
                                    validator: (value) {
                                      if (value == null || value.isEmpty) {
                                        return "phone can't be empty";
                                      }
                                      return null;
                                    },
                                    textAlign:
                                        translate.set_alignment == "right"
                                            ? TextAlign.right
                                            : TextAlign.left,
                                    style: TextStyle(color: primaryColor),
                                    decoration: InputDecoration(
                                      filled: true,
                                      fillColor: primaryColor.withOpacity(0.04),
                                      hintText:
                                          translate.getTxt("username") ?? "",
                                      hintStyle: TextStyle(
                                          color: primaryColor.withOpacity(0.6)),
                                      prefixIcon:
                                          Icon(Icons.person, color: primaryColor),
                                      border: OutlineInputBorder(
                                        borderRadius: BorderRadius.circular(18),
                                        borderSide: BorderSide.none,
                                      ),
                                      focusedBorder: OutlineInputBorder(
                                        borderRadius: BorderRadius.circular(18),
                                        borderSide: BorderSide(
                                          color: secondaryColor,
                                          width: 2,
                                        ),
                                      ),
                                    ),
                                  ),

                                  const SizedBox(height: 30),

                                  /// RESTORE BUTTON
                                  circular
                                      ? const CircularProgressIndicator()
                                      : FormHelper.submitButton(
                                          translate.getTxt("restoreBtn") ?? "",
                                          () async {
                                            if (!globleFormKey.currentState!
                                                .validate()) {
                                              return;
                                            }

                                            setState(() => circular = true);

                                            final response =
                                                await api_backend_post_request({
                                              "action": "change_password",
                                              "phone": _c_phone.text,
                                            });

                                            setState(() => circular = false);

                                            if (response["status"] == "succeed") {
                                              alert_msg(context, "حسنا",
                                                  response["contentMsg"]);
                                            } else if (response["status"] ==
                                                "failed") {
                                              alert_msg(context, "تنبيه",
                                                  response["contentMsg"]);
                                            } else {
                                              alert_msg(context, "خطأ",
                                                  "غير معروف");
                                            }
                                          },
                                          btnColor: secondaryColor,
                                          txtColor: Colors.white,
                                          borderRadius: 30,
                                          width: double.infinity,
                                        ),
                                ],
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),

                    const SizedBox(height: 20),

                    /// BACK TO LOGIN BUTTON OUTSIDE CARD
                    RichText(
                      text: TextSpan(
                        children: [
                          TextSpan(
                            text: translate.getTxt("login") ?? "",
                            style: TextStyle(
                              color: secondaryColor,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
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
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

