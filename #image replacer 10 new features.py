# image replacer 9 for laravel
from flask import Flask, render_template, request, redirect, send_from_directory, url_for, jsonify
import os
from werkzeug.utils import secure_filename
from bs4 import BeautifulSoup
from PIL import Image
import re
import logging

app = Flask(__name__)

# Set the folder where the HTML files are located
HTML_FOLDER = 'resources/views'
ASSETS_FOLDER = 'public'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif', 'webp','svg'}

app.config['HTML_FOLDER'] = HTML_FOLDER
app.config['ASSETS_FOLDER'] = ASSETS_FOLDER

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def get_image_files_from_blade(blade_file):
    with open(blade_file, 'r', encoding='utf-8') as file:
        content = file.read()

    # Find image paths in the asset() syntax
    asset_pattern = re.compile(r'\{\{\s*asset\([\'"]([^\'"]+\.(?:png|jpg|jpeg|gif|webp|svg))[\'"]\)\s*\}\}')
    image_files = asset_pattern.findall(content)

    # Find background images in style attributes
    bg_image_pattern = re.compile(r'background-image\s*:\s*url\([\'"]?([^\'")]+\.(?:png|jpg|jpeg|gif|webp|svg))[\'"]?\)')
    image_files += bg_image_pattern.findall(content)

    return image_files

def replace_image_in_blade(blade_file, old_image, new_image):
    with open(blade_file, 'r', encoding='utf-8') as file:
        content = file.read()

    # Replace <img> tags and background images in style attributes
    content = re.sub(r'(\{\{\s*asset\([\'"])' + re.escape(old_image) + r'([\'"]\)\s*\}\})', r'\1' + new_image + r'\2', content)

    with open(blade_file, 'r', encoding='utf-8') as file:
        soup = BeautifulSoup(file, 'html.parser')
    for elem in soup.find_all(style=True):
        style = elem['style']
        if old_image in style:
            new_style = style.replace(old_image, new_image)
            elem['style'] = new_style

    with open(blade_file, 'w', encoding='utf-8') as file:
        file.write(str(soup))

    with open(blade_file, 'w', encoding='utf-8') as file:
        file.write(content)


def get_image_density(image_path):
    try:
        with Image.open(image_path) as img:
            return img.size # Returns a tuple (width, height)
    except Exception as e:
        logger.error(f"Error getting density for {image_path}: {e}")
        return (0, 0)

@app.route('/')
def index():
    try:
        blade_files = [f for f in os.listdir(HTML_FOLDER) if f.endswith('blade.php')]
        images_info = []
        for blade_file in blade_files:
            blade_path = os.path.join(HTML_FOLDER, blade_file)
            image_files = get_image_files_from_blade(blade_path)
            for image_file in image_files:
                if image_file.startswith('/', 0, 3):
                    image_file = image_file.replace('/', '', 1)
                image_path = os.path.join(ASSETS_FOLDER, image_file)
                if os.path.exists(image_path):
                    density = get_image_density(image_path)
                    image_info = {
                        'name': image_file,
                        'size': os.path.getsize(image_path),
                        'density': f"{density[0]}x{density[1]} px",
                        'path': os.path.join(ASSETS_FOLDER, image_file),
                        'blade_file': blade_file
                    }
                    images_info.append(image_info)
        return render_template('index-laravel9.html', images=images_info)
    except Exception as e:
        logger.error(f"Error in index route: {e}")
        return jsonify(success=False, error=str(e)), 500

@app.route('/public/<path:filename>')
def serve_html_files(filename):
    try:
        return send_from_directory(ASSETS_FOLDER, filename)
    except Exception as e:
        logger.error(f"Error serving file {filename}: {e}")
        return jsonify(success=False, error=str(e)), 500

@app.route('/replace', methods=['POST'])
def replace_image():
    try:
        # Ensure required parameters are provided
        if 'new_image' not in request.files or 'old_image' not in request.form or 'blade_file' not in request.form:
            return jsonify(success=False, error="Missing required parameters")

        file = request.files['new_image']
        old_image = request.form['old_image']
        blade_file = request.form['blade_file']

        if file and allowed_file(file.filename):
            filename = secure_filename(file.filename)
            image_path = os.path.join(ASSETS_FOLDER, filename)
            old_image_path = os.path.join(ASSETS_FOLDER, old_image)

            # Open the uploaded image with Pillow
            img = Image.open(file)

            density = get_image_density(old_image_path)

            desired_size = (density[0], density[1])
            img_resized = img.resize(desired_size)

            # Save the optimized and resized image
            img_resized.save(image_path, optimize=True, quality=100)

            # Remove the old image if it exists
            if os.path.exists(old_image_path):
                os.remove(old_image_path)

            # Rename the new image to the old image's name
            os.rename(image_path, old_image_path)

            # Replace image reference in the blade file
            replace_image_in_blade(os.path.join(HTML_FOLDER, blade_file), old_image, filename)

        return jsonify(success=True, new_image=url_for('serve_html_files', filename=filename))

    except Exception as e:
        logger.error(f"Error in replace_image route: {e}")
        return jsonify(success=False, error=str(e)), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
