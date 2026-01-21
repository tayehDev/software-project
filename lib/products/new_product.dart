import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:snippet_coder_utils/FormHelper.dart';
import 'package:http/http.dart' as http;

import 'package:platform_stores/api/api_backend_method.dart';
import 'package:platform_stores/api/config.dart';

import 'package:platform_stores/products/all_products.dart';


import 'package:platform_stores/public/alert_dialog.dart';
import 'package:platform_stores/public/globals.dart' as globals;

import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';

import 'package:image_picker/image_picker.dart';

class NewProduct extends StatefulWidget {
  final String? row_id;

  NewProduct({this.row_id});

  @override
  State<NewProduct> createState() => _NewProduct();
}

class _NewProduct extends State<NewProduct> {
  final globleFormKey = GlobalKey<FormState>();

  TextEditingController _titleController = TextEditingController();
  TextEditingController _c_price = TextEditingController();
  TextEditingController _descriptionController = TextEditingController();

  bool validate = false;
  bool circular = false;
  bool _isLoadingCategories = true;
  String? errorText;

  String? _selectedOption = "1"; // brand_copy
  String? _selectedCategoryId;
  List<Map<String, dynamic>> _categories = [];

  File? _image;
  String? _networkImageUrl;
  final ImagePicker _picker = ImagePicker();

  final Color primaryColor = HexColor("#011432");
  final Color secondaryColor = HexColor("#e75423");
  final Color lightBackground = HexColor("#f8f8f8");

  @override
  void initState() {
    super.initState();
    _fetchCategories().then((_) {
      if (widget.row_id != null) {
        _fetchPostData(widget.row_id!);
      }
    });
  }

  Future<void> _pickImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _image = File(pickedFile.path);
        _networkImageUrl = null; // إزالة الصورة السابقة عند اختيار صورة جديدة
      });
    }
  }

  Future<void> _fetchCategories() async {
    try {
      var response = await api_backend_post_request({"action": "get_categories"});
      if (response["status"] == "succeed" && response["data"] is List) {
        setState(() {
          _categories = List<Map<String, dynamic>>.from(response["data"]);
          _isLoadingCategories = false;
        });
      } else {
        setState(() {
          _categories = [];
          _isLoadingCategories = false;
        });
        alert_msg(context, "خطأ", "فشل تحميل الفئات من السيرفر.");
      }
    } catch (e) {
      setState(() {
        _isLoadingCategories = false;
      });
      alert_msg(context, "خطأ", "حدث خطأ أثناء الاتصال بالسيرفر.");
    }
  }

  Future<void> _fetchPostData(String row_id) async {
    try {
      var response = await api_backend_post_request({
        "action": "get_product_by_id",
        "product_id": row_id,
        "by_user_id": globals.current_user_id,
      });

      if (response["status"] == "succeed" && response["data"] != null) {
        final post = response["data"];
        setState(() {
          _titleController.text = post["title_product"]?.toString() ?? '';
          _descriptionController.text = post["description"]?.toString() ?? '';
          _c_price.text = post["price"]?.toString() ?? '';
          _selectedCategoryId = post["category"]?.toString();
          _selectedOption = post["brand_copy"]?.toString() ?? "1";
          if (post["img"] != null && post["img"].toString().isNotEmpty) {
            _networkImageUrl = "http://$config_URL$show_img_on_server${post['img']}";
          }
        });
      }
    } catch (e) {
      alert_msg(context, "خطأ", "فشل تحميل بيانات المنتج");
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
              colors: [lightBackground, Colors.white, lightBackground],
            ),
          ),
          child: Form(
            key: globleFormKey,
            child: SingleChildScrollView(
              padding: EdgeInsets.symmetric(vertical: 20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Center(
                    child: Text(
                      widget.row_id == null ? "Create New Product" : "Edit Product",
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: primaryColor,
                      ),
                    ),
                  ),
                  SizedBox(height: 25),

                  // Title
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: TextFormField(
                      controller: _titleController,
                      validator: (value) {
                        if (value!.isEmpty) return "Title can't be empty";
                        return null;
                      },
                      decoration: InputDecoration(
                        labelText: "Title",
                        labelStyle: TextStyle(color: primaryColor),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor, width: 2),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),

                  // Description
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: TextFormField(
                      controller: _descriptionController,
                      maxLines: 3,
                      decoration: InputDecoration(
                        labelText: "Description",
                        labelStyle: TextStyle(color: primaryColor),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor, width: 2),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),

                  // Category
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: _isLoadingCategories
                        ? Center(child: CircularProgressIndicator(color: secondaryColor))
                        : DropdownButtonFormField<String>(
                            value: _selectedCategoryId,
                            hint: Text("Select Category"),
                            items: _categories.map((cat) {
                              return DropdownMenuItem(
                                value: cat["id"].toString(),
                                child: Text(cat["name"]),
                              );
                            }).toList(),
                            onChanged: (val) {
                              setState(() {
                                _selectedCategoryId = val!;
                              });
                            },
                            decoration: InputDecoration(
                              labelText: "Category",
                              labelStyle: TextStyle(color: primaryColor),
                              border: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(15),
                                  borderSide: BorderSide(color: secondaryColor)),
                              focusedBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(15),
                                borderSide: BorderSide(color: secondaryColor, width: 2),
                              ),
                            ),
                          ),
                  ),
                  SizedBox(height: 20),

                  // Price
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: TextFormField(
                      controller: _c_price,
                      validator: (value) {
                        if (value!.isEmpty) return "Price can't be empty";
                        return null;
                      },
                      keyboardType: TextInputType.number,
                      textAlign: TextAlign.center,
                      decoration: InputDecoration(
                        prefixIcon: Icon(Icons.monetization_on, color: secondaryColor),
                        hintText: "Price",
                        hintStyle: TextStyle(color: primaryColor),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(50),
                          borderSide: BorderSide(color: secondaryColor),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(50),
                          borderSide: BorderSide(color: secondaryColor, width: 2),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),

                  // Brand/Copy
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: DropdownButtonFormField<String>(
                      value: _selectedOption,
                      decoration: InputDecoration(
                        labelText: "Product Type",
                        labelStyle: TextStyle(color: primaryColor),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                          borderSide: BorderSide(color: secondaryColor, width: 2),
                        ),
                      ),
                      items: [
                        DropdownMenuItem(value: '1', child: Text('Brand')),
                        DropdownMenuItem(value: '2', child: Text('Copy')),
                      ],
                      onChanged: (value) {
                        setState(() {
                          _selectedOption = value!;
                        });
                      },
                    ),
                  ),
                  SizedBox(height: 25),

                  // Image Picker
                  Center(
                    child: Column(
                      children: [
                        // الصورة السابقة أو المختارة
                        if (_networkImageUrl != null)
                          Container(
                            margin: EdgeInsets.only(bottom: 10),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: secondaryColor, width: 2),
                            ),
                            child: Image.network(
                              _networkImageUrl!,
                              width: 150,
                              height: 150,
                              fit: BoxFit.cover,
                            ),
                          ),
                        if (_image != null)
                          Container(
                            margin: EdgeInsets.only(bottom: 10),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: secondaryColor, width: 2),
                            ),
                            child: Image.file(
                              _image!,
                              width: 150,
                              height: 150,
                              fit: BoxFit.cover,
                            ),
                          ),

                        ElevatedButton.icon(
                          onPressed: _pickImage,
                          icon: Icon(Icons.photo),
                          label: Text("Pick Image"),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: secondaryColor,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(30),
                            ),
                            padding: EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                          ),
                        ),
                      ],
                    ),
                  ),
                  SizedBox(height: 30),

                  // Submit Button
                  Center(
                    child: circular
                        ? CircularProgressIndicator(color: secondaryColor)
                        : FormHelper.submitButton(
                            widget.row_id == null ? "Publish" : "Update",
                            () async {
                              if (_c_price.text.isEmpty || _titleController.text.isEmpty) {
                                setState(() {
                                  validate = false;
                                  errorText = "Fields can't be empty";
                                });
                                return;
                              }

                              String? base64Image;
                              if (_image != null) {
                                List<int> imageBytes = await _image!.readAsBytes();
                                base64Image = base64Encode(imageBytes);
                              }

                              Map<String, dynamic> response_list =
                                  await api_backend_post_request({
                                "action": widget.row_id == null ? "new_product" : "update_product",
                                "product_id": widget.row_id,
                                "title_product": _titleController.text,
                                "price": _c_price.text,
                                "brand_copy": _selectedOption,
                                "by_user_id": globals.current_user_id,
                                "description": _descriptionController.text,
                                "category": _selectedCategoryId,
                                "image": base64Image,
                              });

                              if (response_list["status"] == "succeed") {
                                if (globals.current_user_role_account == 1 || globals.current_user_role_account == 2) {
                                  Navigator.pushReplacement(
                                    context,
                                    MaterialPageRoute(builder: (context) => AllProductsPage()),
                                  );
                                } 
                              } else {
                                alert_msg(context, "خطأ", response_list["contentMsg"] ?? "فشل العملية.");
                              }
                            },
                            btnColor: secondaryColor,
                            borderColor: secondaryColor,
                            txtColor: Colors.white,
                            borderRadius: 30,
                            width: 355,
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
}

