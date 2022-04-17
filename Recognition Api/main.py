import cv2
import imutils
import pytesseract
import re
import os
from flask import Flask, jsonify

app = Flask(__name__)

@app.route('/recognition', methods=["POST"])
def recognition():
    pytesseract.pytesseract.tesseract_cmd = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'

    img = cv2.imread('./images/Placa2.jpg',cv2.IMREAD_COLOR)
    img = imutils.resize(img, width=500 )
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY) #convert to grey scale
    gray = cv2.bilateralFilter(gray, 11, 17, 17) #Blur to reduce noise
    edged = cv2.Canny(gray, 30, 200) #Perform Edge detection
    cnts,new = cv2.findContours(edged.copy(), cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
    img1=img.copy()
    cv2.drawContours(img1,cnts,-1,(0,255,0),3)
    cnts = sorted(cnts, key = cv2.contourArea, reverse = True)[:30]

    img2 = img.copy()
    cv2.drawContours(img2,cnts,-1,(0,255,0),3)

    path, dirs, files = next(os.walk("./plates"))
    idx = len(files) + 1
    # loop over contours
    for c in cnts:
    # approximate the contour
        peri = cv2.arcLength(c, True)
        approx = cv2.approxPolyDP(c, 0.018 * peri, True)
        if len(approx) == 4: #chooses contours with 4 corners
            x,y,w,h = cv2.boundingRect(c) #finds co-ordinates of the plate
            new_img=img[y:y+h,x:x+w]
            cv2.imwrite('./plates/RecognitionResult-'+str(idx)+'.png',new_img) #stores the new image
            break

    Cropped_loc='./plates/RecognitionResult-'+str(idx)+'.png' #the filename of cropped image
    pytesseract.pytesseract.tesseract_cmd=r"C:\\Program Files\\Tesseract-OCR\\tesseract.exe" #exe file for using ocr 

    text=pytesseract.image_to_string(Cropped_loc,lang='eng') #converts image characters to string

    print("Number is:" ,re.sub('[^A-Za-z0-9]+', '', text))
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    return jsonify({'Plate': re.sub('[^A-Za-z0-9]+', '', text)})

app.run(debug=True)
    