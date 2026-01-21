import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:snippet_coder_utils/FormHelper.dart';
import 'package:image_picker/image_picker.dart';

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';

class NewUser extends StatefulWidget {
  final String? row_id;
  NewUser({this.row_id});

  @override
  State<NewUser> createState() => _NewUserState();
}

class _NewUserState extends State<NewUser> {
  final _formKey = GlobalKey<FormState>();

  final TextEditingController _fullnameController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _latitudeController = TextEditingController();
  final TextEditingController _longitudeController = TextEditingController();

  String? _areaId;
  String _imgName = '';
  File? _imageFile;
  final ImagePicker _picker = ImagePicker();

  List<Map<String, dynamic>> _areas = [];
  bool _loadingAreas = true;
  bool _submitting = false;

  int _userRoleAccount = 3;

  @override
  void initState() {
    super.initState();
    _fetchAreasFromProfile().then((_) {
      if (widget.row_id != null) {
        _fetchUserById(widget.row_id!);
      }
    });
  }

  @override
  void dispose() {
    _fullnameController.dispose();
    _phoneController.dispose();
    _passwordController.dispose();
    _addressController.dispose();
    _latitudeController.dispose();
    _longitudeController.dispose();
    super.dispose();
  }

  Future<void> _fetchAreasFromProfile() async {
    try {
      var response = await api_backend_post_request({
        "action": "show_profile",
        "by_user_id": globals.current_user_id,
      });

      if (response != null && response["status"] == "succeed") {
        final data = response["dataView"];
        if (data != null && data["cities"] is List) {
          setState(() {
            _areas = List<Map<String, dynamic>>.from(data["cities"].map((e) => {
                  'id': e['id'],
                  'name': e['name']?.toString() ?? ''
                }));
            _loadingAreas = false;
          });
          return;
        }
      }
      setState(() {
        _areas = [];
        _loadingAreas = false;
      });
    } catch (e) {
      setState(() {
        _loadingAreas = false;
      });
      alert_msg(context, 'خطأ', 'فشل تحميل قائمة المناطق.');
    }
  }

  Future<void> _fetchUserById(String id) async {
    try {
      var response = await api_backend_post_request({
        "action": "get_user_by_id",
        "user_id": id,
        "by_user_id": globals.current_user_id,
      });

      if (response != null && response["status"] == "succeed" && response["data"] != null) {
        final user = response["data"];
        setState(() {
          _fullnameController.text = user['fullname']?.toString() ?? '';
          _phoneController.text = user['phone']?.toString() ?? '';
          _addressController.text = user['address']?.toString() ?? '';
          _latitudeController.text = user['latitude']?.toString() ?? '';
          _longitudeController.text = user['longitude']?.toString() ?? '';
          _areaId = user['area_id']?.toString();
          _imgName = user['img']?.toString() ?? '';
          _userRoleAccount = int.tryParse(user['user_role_account']?.toString() ?? '') ?? 3;
        });
      }
    } catch (e) {
      alert_msg(context, 'خطأ', 'فشل تحميل بيانات المستخدم');
    }
  }

  Future<void> _pickImage() async {
    final picked = await _picker.pickImage(source: ImageSource.gallery);
    if (picked != null) {
      setState(() {
        _imageFile = File(picked.path);
      });
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _submitting = true;
    });

    String? base64Image;
    if (_imageFile != null) {
      final bytes = await _imageFile!.readAsBytes();
      base64Image = base64Encode(bytes);
    }

    final payload = {
      "action": widget.row_id == null ? "new_user" : "update_user",
      "user_id": widget.row_id,
      "fullname": _fullnameController.text,
      "phone": _phoneController.text,
      if (_passwordController.text.isNotEmpty) "password": _passwordController.text,
      "address": _addressController.text,
      "latitude": _latitudeController.text,
      "longitude": _longitudeController.text,
      "area_id": _areaId,
      "user_role_account": _userRoleAccount.toString(),
      "image": base64Image,
      "by_user_id": globals.current_user_id,
    };

    try {
      var response = await api_backend_post_request(payload);

      if (response != null && response["status"] == "succeed") {
        alert_msg(context, 'نجاح', response["contentMsg"] ?? 'تم الحفظ بنجاح');
        Navigator.pop(context);
      } else {
        alert_msg(context, 'خطأ', response?["contentMsg"] ?? 'فشل العملية');
      }
    } catch (e) {
      alert_msg(context, 'خطأ', 'حدث خطأ أثناء الاتصال بالسيرفر');
    } finally {
      setState(() {
        _submitting = false;
      });
    }
  }

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
              colors: [HexColor('#FFFFFF'), HexColor('#F5F5F5')],
            ),
          ),
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Center(
                    child: Text(
                      widget.row_id == null ? 'Create New User' : 'Edit User',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: HexColor('#011432'),
                      ),
                    ),
                  ),
                  SizedBox(height: 25),

                  // Avatar + Image Picker
                  Center(
                    child: Column(
                      children: [
                        GestureDetector(
                          onTap: _pickImage,
                          child: CircleAvatar(
                            radius: 55,
                            backgroundColor: HexColor('#011432'),
                            child: _imageFile == null
                                ? (_imgName.isEmpty
                                    ? Icon(Icons.person, size: 55, color: Colors.white)
                                    : null)
                                : null,
                            backgroundImage: _imageFile != null
                                ? FileImage(_imageFile!)
                                : (_imgName.isNotEmpty
                                    ? NetworkImage(
                                        'http://$config_URL$show_img_on_server$_imgName')
                                    : null) as ImageProvider?,
                          ),
                        ),
                        SizedBox(height: 8),
                        ElevatedButton.icon(
                          onPressed: _pickImage,
                          icon: Icon(Icons.photo, color: Colors.white),
                          label: Text(
                            'Choose Image',
                            style: TextStyle(color: Colors.white), // النص أبيض
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: HexColor('#E75423'),
                            shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(12)),
                            padding: EdgeInsets.symmetric(horizontal: 18, vertical: 10),
                          ),
                        ),
                      ],
                    ),
                  ),

                  SizedBox(height: 25),

                  _buildTextField(controller: _fullnameController, label: 'Full name', icon: Icons.person),
                  _buildTextField(controller: _phoneController, label: 'Phone', icon: Icons.phone, keyboardType: TextInputType.phone),

                  _loadingAreas ? Center(child: CircularProgressIndicator()) : _buildAreaDropdown(),
                  _buildTextField(controller: _addressController, label: 'Address', icon: Icons.location_on),

                  Row(
                    children: [
                      Expanded(child: _buildTextField(controller: _latitudeController, label: 'Latitude', icon: Icons.place, keyboardType: TextInputType.number)),
                      SizedBox(width: 12),
                      Expanded(child: _buildTextField(controller: _longitudeController, label: 'Longitude', icon: Icons.place, keyboardType: TextInputType.number)),
                    ],
                  ),

                  _buildTextField(controller: _passwordController, label: 'Password (leave blank to keep current)', icon: Icons.lock, obscureText: true),

                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    child: DropdownButtonFormField<int>(
                      value: _userRoleAccount,
                      decoration: InputDecoration(
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                        labelText: 'User Role',
                      ),
                      items: [
                        DropdownMenuItem(value: 1, child: Text('Admin')),
                        DropdownMenuItem(value: 2, child: Text('Seller')),
                        DropdownMenuItem(value: 3, child: Text('Customer')),
                        DropdownMenuItem(value: 4, child: Text('Courier')),
                      ],
                      onChanged: (v) => setState(() => _userRoleAccount = v ?? 3),
                    ),
                  ),

                  SizedBox(height: 20),

                  _submitting
                      ? Center(child: CircularProgressIndicator())
                      : SizedBox(
                          width: double.infinity,
                          child: FormHelper.submitButton(
                            widget.row_id == null ? 'Create User' : 'Update User',
                            () async => _submitForm(),
                            btnColor: HexColor('#E75423'),
                            borderColor: HexColor('#ffffff'),
                            txtColor: Colors.white,
                            borderRadius: 12,
                            width: MediaQuery.of(context).size.width,
                          ),
                        ),

                  SizedBox(height: 30),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    TextInputType keyboardType = TextInputType.text,
    bool obscureText = false,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: TextFormField(
        controller: controller,
        keyboardType: keyboardType,
        obscureText: obscureText,
        validator: (v) => (v == null || v.isEmpty) ? 'Required' : null,
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: Icon(icon, color: HexColor('#011432')),
          fillColor: Colors.white,
          filled: true,
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        ),
      ),
    );
  }

  Widget _buildAreaDropdown() {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: DropdownButtonFormField<String>(
        value: _areaId,
        decoration: InputDecoration(
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          labelText: 'Area',
          fillColor: Colors.white,
          filled: true,
        ),
        items: _areas.map((area) {
          return DropdownMenuItem(value: area['id'].toString(), child: Text(area['name'].toString()));
        }).toList(),
        onChanged: (v) => setState(() => _areaId = v),
      ),
    );
  }
}

