library platform_stores.translate;

String your_language = "en";
String set_alignment = "left";

final Map<String, Map<String, String>> dict = {
  "title": {
    "ar": "Stores",
    "en": "PlatformStores",
  },
  "loginBtn": {
    "ar": "تسجيل دخول",
    "en": "Login",
  },
  "username": {
    "ar": "اسم المستخدم",
    "en": "Username",
  },
  "password": {
    "ar": "كلمة المرور",
    "en": "Password",
  },
  "repassword": {
    "ar": "اعادة كلمة المرور",
    "en": "rePassword",
  },
  "forgetPassword?": {
    "ar": "هل نسيت كلمة المرور؟",
    "en": "Forget Your Password?",
  },
  "restoreBtn": {
    "ar": "استعادة كلمة المرور",
    "en": "Restore Password",
  },
  "newAccount?": {
    "ar": "هل تريد انشاء حساب؟",
    "en": "Create New Account?",
  },
  "login": {
    "ar": "تسجيل دخول",
    "en": "Login",
  },
 "newAccount": {
    "ar": "انشاء حساب",
    "en": "Create New Account",
  },

  "bottomBar__home": {
    "ar": "الرئيسية",
    "en": "Home",
  },
  "bottomBar__myAccount": {
    "ar": "حسابي",
    "en": "My Account",
  },
  "bottomBar__logout": {
    "ar": "خروج",
    "en": "Logout",
  },
  "bottomBar__notifications": {
    "ar": "اشعارات",
    "en": "Notifications",
  },
  
"area": {
    "ar": "المدينة",
    "en": "City",
  },

"address": {
    "ar": "العنوان",
    "en": "Address",
  },

  "phone": {
    "ar": "الهاتف",
    "en": "Phone",
  },
  "edit": {
    "ar": "تحرير",
    "en": "Edit",
  },
  
  
  "save": {
    "ar": "حفظ",
    "en": "Save",
  },
  "fullname": {
    "ar": "الاسم الكامل",
    "en": "Full Name",
  },
  "business_name": {
    "ar": "الاسم التجاري",
    "en": "Business Name",
  },

  "username": {
    "ar": "اسم المستخدم",
    "en": "Username",
  },
  "password": {
    "ar": "كلمة المرور",
    "en": "Password",
  },
  "back": {
    "ar": "للخلف",
    "en": "Back",
  },
  
  
  
  // Add more strings as needed
};

// Function to retrieve a string based on the selected language
String? getTxt(String key) {
  set_alignment = your_language == "ar" ? "right" : "left";
  return dict[key]?[your_language];
}
