import 'dart:async';
import 'dart:io';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:hexcolor/hexcolor.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/profile/show_profile.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/public/translate.dart' as translate;

import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';

// الألوان الرئيسية
final Color primaryColor = HexColor("#011432"); // أزرق داكن
final Color secondaryColor = HexColor("#e75423"); // برتقالي أحمر

// دالة لجلب بيانات البروفايل
Future<Map<String, dynamic>> createProfileData(int by_user_id) async {
  Uri uri = useHttps
      ? Uri.https(config_URL, config_unencodedPath)
      : Uri.http(config_URL, config_unencodedPath);

  final response = await http.post(
    uri,
    headers: {'Content-Type': 'application/json; charset=UTF-8'},
    body: jsonEncode({"action": "show_profile", "by_user_id": by_user_id}),
  );

  if (response.statusCode == 200) {
    dynamic jsonD = jsonDecode(response.body);
    if (jsonD['status'] == "succeed") {
      return jsonD['dataView'];
    } else {
      throw Exception(jsonD['dataView'] ?? 'Failed to load profile data');
    }
  } else {
    throw Exception('Failed to load profile data. Status code: ${response.statusCode}');
  }
}

// صفحة تعديل البروفايل
class EditProfilePage extends StatefulWidget {
  @override
  State<EditProfilePage> createState() => _EditProfilePageState();
}

class _EditProfilePageState extends State<EditProfilePage> {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: topBar(context),
        bottomNavigationBar: footer(context),
        body: Container(
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
          child: buildFutureBuilder(globals.current_user_id!),
        ),
      ),
    );
  }

  FutureBuilder<Map<String, dynamic>> buildFutureBuilder(int userId) {
    return FutureBuilder<Map<String, dynamic>>(
      future: createProfileData(userId),
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          return PageContent(userId, snapshot.data!);
        } else if (snapshot.hasError) {
          return Center(child: Text('Error: ${snapshot.error}', style: TextStyle(color: primaryColor)));
        }
        return const Center(child: CircularProgressIndicator());
      },
    );
  }
}

// محتوى الصفحة بعد جلب البيانات
class PageContent extends StatefulWidget {
  final int by_user_id;
  final Map<String, dynamic> profileData;

  PageContent(this.by_user_id, this.profileData);

  @override
  State<PageContent> createState() => _PageContentState();
}

class _PageContentState extends State<PageContent> {
  bool hidepassWord = true;
  File? _image;
  final picker = ImagePicker();

  String? _area_id;
  List<Map<String, dynamic>> _citiesJSON = [];
  String _img_name = "";

  TextEditingController _fullname = TextEditingController();
  TextEditingController _business_name = TextEditingController();
  TextEditingController _phone = TextEditingController();
  TextEditingController _password = TextEditingController();
  TextEditingController _address = TextEditingController();

  @override
  void initState() {
    super.initState();
    _fullname.text = widget.profileData['fullname']?.toString() ?? '';
    _business_name.text = widget.profileData['business_name']?.toString() ?? '';
    _phone.text = widget.profileData['phone']?.toString() ?? '';
    _address.text = widget.profileData['address']?.toString() ?? '';
    _area_id = widget.profileData['area_id']?.toString();
    _img_name = widget.profileData['img']?.toString() ?? '';

    _citiesJSON = List<Map<String, dynamic>>.from(
      widget.profileData['cities']?.map((area) => {
        'id': area['id'] ?? 0,
        'name': area['name']?.toString() ?? ''
      })?.toList() ?? []
    );
  }

  @override
  void dispose() {
    _fullname.dispose();
    _business_name.dispose();
    _phone.dispose();
    _password.dispose();
    _address.dispose();
    super.dispose();
  }

  Future<void> choiceImage() async {
    final pickedImage = await picker.pickImage(source: ImageSource.gallery);
    setState(() {
      _image = pickedImage == null ? null : File(pickedImage.path);
    });
  }

  Future<void> uploadImage() async {
    if (_image == null) {
      Fluttertoast.showToast(msg: "Please select an image first.");
      return;
    }

    final uri = Uri.parse("http://" + config_URL + config_unencodedPath_upload_img);
    var request = http.MultipartRequest('POST', uri);
    request.fields['by_user_id'] = globals.current_user_id.toString();
    var pic = await http.MultipartFile.fromPath("image", _image!.path);
    request.files.add(pic);
    var response = await request.send();

    if (response.statusCode == 200) {
      Fluttertoast.showToast(msg: 'Image Uploaded Successfully');
    } else {
      Fluttertoast.showToast(msg: 'Image Not Uploaded. Status: ${response.statusCode}');
    }
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        children: [
          const SizedBox(height: 20),
          Center(
            child: IconButton(
              icon: Icon(Icons.edit, color: primaryColor),
              onPressed: choiceImage,
            ),
          ),
          Center(
            child: SizedBox(
              width: 150,
              height: 150,
              child: _image != null
                  ? CircleAvatar(
                      backgroundImage: FileImage(_image!),
                      radius: 60,
                    )
                  : (_img_name.isNotEmpty
                      ? CircleAvatar(
                          backgroundImage: NetworkImage(
                              "http://" + config_URL + show_img_on_server + _img_name),
                          radius: 60,
                        )
                      : CircleAvatar(
                          radius: 60,
                          child: Icon(Icons.image, size: 50.0, color: primaryColor),
                        )),
            ),
          ),
          const SizedBox(height: 20),
          // زر رفع الصورة
          Center(
            child: ElevatedButton(
              child: Text(
                '${translate.getTxt("save")}',
                style: TextStyle(fontWeight: FontWeight.bold, color: secondaryColor),
              ),
              onPressed: uploadImage,
              style: ElevatedButton.styleFrom(
                side: BorderSide(width: 1, color: secondaryColor),
                backgroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20),
                ),
              ),
            ),
          ),
          const SizedBox(height: 20),
          // الصندوق الرئيسي
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: Container(
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.7),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: primaryColor),
              ),
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildTextField(controller: _fullname, label: '${translate.getTxt("fullname")}', icon: Icons.person),
                  _buildTextField(controller: _business_name, label: '${translate.getTxt("business_name")}', icon: Icons.group),
                  _buildCityDropdown(val: _area_id, label: '${translate.getTxt("area")}', citiesJSON: _citiesJSON),
                  _buildTextField(controller: _address, label: '${translate.getTxt("address")}', icon: Icons.location_on),
                  _buildTextField(controller: _phone, label: '${translate.getTxt("phone")}', icon: Icons.phone, keyboardType: TextInputType.phone),
                  _buildPasswordField(controller: _password, label: '${translate.getTxt("password")}', obscureText: hidepassWord, toggleVisibility: () {
                    setState(() { hidepassWord = !hidepassWord; });
                  }),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),
          // زر الحفظ
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () async {
                  Map<String, dynamic> response_list = await api_backend_post_request({
                    "action": "update_your_profile",
                    "by_user_id": widget.by_user_id,
                    "fullname": _fullname.text,
                    "business_name": _business_name.text,
                    "area_id": _area_id,
                    "address": _address.text,
                    "phone": _phone.text,
                    "password": _password.text
                  });

                  if (response_list["status"] == "succeed") {
                    alert_msg(context, "Success", response_list["contentMsg"]);
                    Navigator.pop(context);
                    Navigator.push(
                      context,
                      MaterialPageRoute(builder: (context) => ShowProfile(widget.by_user_id)),
                    );
                  } else if (response_list["status"] == "failed") {
                    alert_msg(context, "Error", response_list["contentMsg"]);
                  } else {
                    alert_msg(context, "Error", "Unknown error occurred");
                  }
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: primaryColor,
                  padding: const EdgeInsets.symmetric(vertical: 15),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                child: Text('${translate.getTxt("save")}', style: const TextStyle(color: Colors.white, fontSize: 16)),
              ),
            ),
          ),
          const SizedBox(height: 40),
        ],
      ),
    );
  }

  // دوال الحقول
  Widget _buildTextField({required TextEditingController controller, required String label, required IconData icon, TextInputType keyboardType = TextInputType.text}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: TextFormField(
        controller: controller,
        keyboardType: keyboardType,
        style: TextStyle(color: primaryColor),
        decoration: InputDecoration(
          labelText: label,
          labelStyle: TextStyle(color: primaryColor),
          prefixIcon: Icon(icon, color: secondaryColor),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: primaryColor)),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: secondaryColor, width: 2)),
        ),
      ),
    );
  }

  Widget _buildCityDropdown({required String? val, required String label, required List<Map<String, dynamic>> citiesJSON}) {
    final hasValue = citiesJSON.any((area) => area["id"].toString() == val);
    final dropdownValue = hasValue ? val : null;

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: DropdownButtonFormField<String>(
        value: dropdownValue,
        onChanged: (String? newValue) { setState(() { _area_id = newValue; }); },
        items: [
          DropdownMenuItem(value: null, child: Text("Select Area", style: TextStyle(color: primaryColor))),
          ...citiesJSON.map((area) {
            return DropdownMenuItem(value: area["id"].toString(), child: Text(area["name"].toString(), style: TextStyle(color: primaryColor)));
          }).toList(),
        ],
        decoration: InputDecoration(
          labelText: label,
          labelStyle: TextStyle(color: primaryColor),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: primaryColor)),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: secondaryColor, width: 2)),
        ),
        dropdownColor: Colors.white,
      ),
    );
  }

  Widget _buildPasswordField({required TextEditingController controller, required String label, required bool obscureText, required VoidCallback toggleVisibility}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: TextFormField(
        controller: controller,
        obscureText: obscureText,
        style: TextStyle(color: primaryColor),
        decoration: InputDecoration(
          labelText: label,
          labelStyle: TextStyle(color: primaryColor),
          prefixIcon: Icon(Icons.lock, color: secondaryColor),
          suffixIcon: IconButton(
            icon: Icon(obscureText ? Icons.visibility : Icons.visibility_off, color: secondaryColor),
            onPressed: toggleVisibility,
          ),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: primaryColor)),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: secondaryColor, width: 2)),
        ),
      ),
    );
  }
}

