# encoding:utf-8
try:
    from PIL import Image
except ImportError:
    import Image
import pytesseract
import sys

img_path = sys.argv[1]

# Simple image to string
print(pytesseract.image_to_string(Image.open(img_path)))
