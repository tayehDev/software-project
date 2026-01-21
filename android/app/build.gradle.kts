plugins {
    id("com.android.application")
    id("kotlin-android")
    id("dev.flutter.flutter-gradle-plugin")
}

android {
    namespace = "com.example.platform_stores"
    compileSdk = 35   // أو flutter.compileSdkVersion إذا كانت >= 33

    ndkVersion = "27.0.12077973"

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }

    kotlinOptions {
        jvmTarget = "11"
    }

defaultConfig {
    applicationId = "com.example.platform_stores"
    minSdk = flutter.minSdkVersion        // احتفظ بالقيمة الحالية
    targetSdk = 35                        // رفع من 27 إلى 33
    versionCode = flutter.versionCode
    versionName = flutter.versionName
}


    buildTypes {
        getByName("release") {
            signingConfig = signingConfigs.getByName("debug")
            isMinifyEnabled = false
            isShrinkResources = false
        }
    }
}

flutter {
    source = "../.."
}

