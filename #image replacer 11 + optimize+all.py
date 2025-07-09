import os
from flask import Flask, render_template, request, jsonify, send_from_directory, url_for
from werkzeug.utils import secure_filename
from bs4 import BeautifulSoup
from PIL import Image
import re
import logging

app = Flask(__name__)

# Configuration settings
HTML_FOLDER = 'resources/views/'
ASSETS_FOLDER = 'public/'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'}

app.config['HTML_FOLDER'] = HTML_FOLDER
app.config['ASSETS_FOLDER'] = ASSETS_FOLDER

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def allowed_file(filename):
    """Check if the file is an allowed image type."""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def get_image_files_from_blade(blade_file):
    """Extract image paths from Blade file using asset() syntax or style attributes."""
    with open(blade_file, 'r', encoding='utf-8') as file:
        content = file.read()

    # Regex for asset() syntax
    # asset_pattern = re.compile(r'\{\{\s*asset\([\'"]([^\'"]+\.(?:png|jpg|jpeg|gif|webp|svg))[\'"]\)\s*\}\}')
    asset_pattern = re.compile(r'[\'"]([^\'"]+\.(?:png|jpg|jpeg|gif|webp|svg))[\'"]')
    image_files = asset_pattern.findall(content)

    # Regex for background images in style attributes
    bg_image_pattern = re.compile(r'background-image\s*:\s*url\([\'"]?([^\'")]+\.(?:png|jpg|jpeg|gif|webp|svg))[\'"]?\)')
    image_files += bg_image_pattern.findall(content)

    return image_files

def get_image_density(image_path):
    """Get image dimensions as density in pixels."""
    try:
        with Image.open(image_path) as img:
            return img.size  # Returns a tuple (width, height)
    except Exception as e:
        logger.error(f"Error getting density for {image_path}: {e}")
        return (0, 0)

def get_all_blade_files():
    """Retrieve all Blade (.blade.php) files recursively from the HTML folder."""
    blade_files = []
    for root, _, files in os.walk(app.config['HTML_FOLDER']):
        for file in files:
            if file.endswith('.blade.php'):
                blade_files.append(os.path.join(root, file))
    return blade_files

@app.route('/')
def index():
    """Main route to display images found in Blade files and their details."""
    try:
        blade_files = get_all_blade_files()
        images_info = []
        blade_files_tabs = []
        image_names_set = set()
        for blade_file in blade_files:
            image_files = get_image_files_from_blade(blade_file)
            for image_file in image_files:
                # Resolve full path for the image
                image_path = os.path.join(app.config['ASSETS_FOLDER'], image_file.lstrip('/'))
                if os.path.exists(image_path):
                    if blade_file not in blade_files_tabs:
                        blade_files_tabs.append(blade_file)
                    density = get_image_density(image_path)
                    if image_file not in image_names_set:
                        image_names_set.add(image_file)
                        image_info = {
                            'name': image_file,
                            'size': round(os.path.getsize(image_path) / 1024, 1),  # Size in KB
                            'density': f"{density[0]}x{density[1]} px",
                            'path': image_path,
                            'blade_file': blade_file
                        }
                        images_info.append(image_info)

                else:
                    logger.info(f"File not found: {image_path}")
        return render_template('index.html', images=images_info, blade_files=blade_files_tabs)
    except Exception as e:
        logger.error(f"Error in index route: {e}")
        return jsonify(success=False, error=str(e)), 500

@app.route('/public/<path:filename>')
def serve_html_files(filename):
    """Serve image files from the public directory."""
    try:
        return send_from_directory(app.config['ASSETS_FOLDER'], filename)
    except Exception as e:
        logger.error(f"Error serving file {filename}: {e}")
        return jsonify(success=False, error=str(e)), 500

@app.route('/optimize-all-images', methods=['POST'])
def optimize_all_images():
    """Optimize all images found in Blade files if they exceed size threshold."""
    try:
        images_info = []
        missing_files = []
        for blade_file in get_all_blade_files():
            image_files = get_image_files_from_blade(blade_file)
            for image_file in image_files:
                # Resolve the full path to the image
                image_path = os.path.join(app.config['ASSETS_FOLDER'], image_file.lstrip('/'))

                # Check if the image file exists
                if not os.path.exists(image_path):
                    missing_files.append(image_file)
                    continue

                # Optimize if the file size is above the threshold (100 KB)
                if round(os.path.getsize(image_path) / 1024, 1) > 100:
                    with Image.open(image_path) as img:
                        # if img.mode in ("RGBA", "P","RGB"):
                        #     img = img.convert("RGB")

                        density = get_image_density(image_path)
                        img_resized = img.resize(density)

                        # Save optimized image with JPEG format
                        img_resized.save(image_path, format="WEBP", optimize=True, quality=100, progressive=True)

                    # Collect optimized image info
                    images_info.append({
                        'name': image_file,
                        'size': round(os.path.getsize(image_path) / 1024, 1),  # Size in KB
                        'density': f"{density[0]}x{density[1]} px",
                        'path': image_path,
                        'blade_file': blade_file
                    })

        return jsonify(success=True, optimized_images=images_info, missing_files=missing_files)
    except Exception as e:
        logger.error(f"Error in optimize_all_images route: {e}")
        return jsonify(success=False, error=str(e)), 500

@app.route('/replace', methods=['POST'])
def replace_image():
    """Replace an image file in the specified Blade file and optionally optimize it."""
    try:
        # Validate request parameters
        if 'new_image' not in request.files or 'old_image' not in request.form or 'blade_file' not in request.form:
            return jsonify(success=False, error="Missing required parameters")

        file = request.files['new_image']
        old_image = request.form['old_image']
        optimize = 'optimize' in request.form  # Check if optimization is requested

        if file and allowed_file(file.filename):
            filename = secure_filename(file.filename)
            new_image_path = os.path.join(ASSETS_FOLDER, filename)
            # if old_image.startwith('/'):
            old_image_path = os.path.join(ASSETS_FOLDER, old_image.lstrip('/'))

            if optimize:
                # Open and resize the uploaded image based on old image density
                img = Image.open(file)
                density = get_image_density(old_image_path)
                img_resized = img.resize(density)
                img_resized.save(new_image_path,format="WEBP",optimize=True, quality=100, progressive=True)
            else:
                file.save(new_image_path)

            # Replace old image file if it exists
            if os.path.exists(old_image_path):
                os.remove(old_image_path)
            os.rename(new_image_path, old_image_path)

            # Update image reference in the Blade file
            # replace_image_in_blade(blade_file, old_image, filename)

        return jsonify(success=True, new_image=url_for('serve_html_files', filename=filename))
    except Exception as e:
        logger.error(f"Error in replace_image route: {e,old_image_path}")
        return jsonify(success=False, error=str(e)), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
