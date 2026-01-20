from ultralytics import YOLO
import cv2
import mysql.connector
import os
import time


'''
#lib
pip3 install mysql-connector-python
pip3 install ultralytics
'''

'''
=====================on windows=========================
1- download and install python ide
2- open folder by visual studio code to run termial on file path
3- open terminal
4- run command
pip3 install mysql-connector-python
pip3 install ultralytics

5 - run python file
python main.py
========================================================
'''


# ===============================
# إعداد YOLO (تحميل مرة واحدة فقط)
# ===============================
model = YOLO("yolov8n.pt")

# مسار الصور
IMAGES_PATH = "../uploads/"

print("AI Image Analyzer Started ⏳")

# ===============================
# Loop كل 10 ثواني
# ===============================
while True:
    try:
        # ===============================
        # الاتصال بقاعدة البيانات
        # ===============================
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="asdfsss",
            database="platform_stores"
        )
        cursor = db.cursor(dictionary=True)

        # ===============================
        # جلب الصور التي لم تُحلل
        # ===============================
        cursor.execute("""
            SELECT id, img
            FROM products
            WHERE ai_analysis = 'wait'
            LIMIT 10
        """)

        products = cursor.fetchall()

        if not products:
            print("No images to analyze...")
        else:
            print(f"Found {len(products)} images to analyze")

        # ===============================
        # تحليل الصور
        # ===============================
        for product in products:
            product_id = product["id"]
            image_name = product["img"]
            image_path = os.path.join(IMAGES_PATH, image_name)

            if not os.path.exists(image_path):
                print(f"Image not found: {image_path}")
                continue

            image = cv2.imread(image_path)
            results = model(image)

            classes_found = set()

            for r in results:
                for box in r.boxes:
                    cls_name = model.names[int(box.cls[0])]
                    classes_found.add(cls_name)

            # نتيجة التحليل
            if classes_found:
                analysis_result = ", ".join(sorted(classes_found))
            else:
                analysis_result = "no_objects"

            # ===============================
            # تحديث قاعدة البيانات
            # ===============================
            cursor.execute("""
                UPDATE products
                SET ai_analysis = %s
                WHERE id = %s
            """, (analysis_result, product_id))

            db.commit()
            print(f"Product {product_id} updated → {analysis_result}")

        cursor.close()
        db.close()

    except Exception as e:
        print("ERROR:", e)

    # ===============================
    # انتظار 10 ثواني
    # ===============================
    time.sleep(10)

