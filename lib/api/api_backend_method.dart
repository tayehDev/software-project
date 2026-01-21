import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/globals.dart' as globals;


Map<String, dynamic> api_backend_post_response_sync(String url, String unencodedPath , Map<String, String> header,Map<String,dynamic> requestBody) { 

    Map<String, dynamic> response_list={

    };
    
    Uri uri;
    if (useHttps) {
      uri = Uri.https(url, unencodedPath);
    } else {
      uri = Uri.http(url, unencodedPath);
    }
  
    http.post(
      uri,
      headers: header,
      body: jsonEncode(requestBody),
      ).then((response) {
        
        print("Response status: ${response.statusCode}");
        print("Response body: ${response.body}");
        
        if (response.statusCode == 200) {
            response_list = jsonDecode(response.body);
            //return response_list;    
          } else
            throw Exception('We were not able to successfully download the json data.');
            
            
        
    });
    



    return response_list;  
}

Future<Map<String, dynamic>> api_backend_post_request_custom(String url, String unencodedPath , Map<String, String> header,Map<String,dynamic> post_data) async { 
      
      Uri uri;
      if (useHttps) {
        uri = Uri.https(url, unencodedPath);
      } else {
        uri = Uri.http(url, unencodedPath);
      }

      
      final response = await http.post(
        uri,
        headers: header,
        body: jsonEncode(post_data),
      );
      print(response.statusCode);
      print(response.body);
      
      if (response.statusCode == 200) {
        Map<String, dynamic> response_list = jsonDecode(response.body);
        return response_list;    
      } else
        throw Exception('We were not able to successfully download the json data.');
        
}


Future<Map<String, dynamic>> api_backend_post_request(Map<String,dynamic> post_data) async { 
      
      Uri uri;
      if (useHttps) {
        uri = Uri.https(config_URL, config_unencodedPath);
      } else {
        uri = Uri.http(config_URL, config_unencodedPath);
      }
      
      final response = await http.post(
        uri,
        headers: config_post_headers,
        body: jsonEncode(post_data),
      );
      print(response.statusCode);
      print(response.body);
      
      if (response.statusCode == 200) {
        Map<String, dynamic> response_list = jsonDecode(response.body);
        if (response.body.isEmpty) {
          return {
            "status": "failed",
            "contentMsg": "Empty response from server"
          };
        }
        return response_list;    
      } else
        throw Exception('We were not able to successfully download the json data.');
        
}




Future<http.Response> api_backend_http_post_response(Map<String,dynamic> post_data) async{
    
    Uri uri;
    if (useHttps) {
      uri = Uri.https(config_URL, config_unencodedPath);
    } else {
      uri = Uri.http(config_URL, config_unencodedPath);
    }
    
    final response = await http.post(
        uri,
        headers: config_post_headers,
        body: jsonEncode(post_data),
      );
    return response;
}


Future<http.Response> api_backend_http_get_response(String url_parameters) {
  if (useHttps) {
    return http.get(Uri.parse("https://"+config_URL+config_unencodedPath+url_parameters));
  } else {
    return http.get(Uri.parse("http://"+config_URL+config_unencodedPath+url_parameters));
  }
  
}




Future<int> uploadImage(String image_path)async{

  Uri uri;
  if (useHttps) {
    uri = Uri.https(config_URL, config_unencodedPath_upload_img);
  } else {
    uri = Uri.http(config_URL, config_unencodedPath_upload_img);
  }
  
  //final uri = Uri.parse(config_URL+config_unencodedPath_upload_img);//old
  
  var request = http.MultipartRequest('POST',uri);
  request.fields['current_user_id'] = globals.current_user_id.toString();
  var pic = await http.MultipartFile.fromPath("image", image_path);
  request.files.add(pic);
  var response = await request.send();

  if (response.statusCode == 200) {
    print('Image Uploded');
    return 1;
  }else{
    print('Image Not Uploded');
    return 0;
  }
  
}


  



