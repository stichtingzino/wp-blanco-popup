# WP Blanco Popup

A lightweight WordPress plugin designed to easily add clean, blank popup functionality to your website.

## 🚀 Features
* **Lightweight & Fast:** Zero bloat, no heavy frameworks or unnecessary scripts.
* **Automated Releases:** Automatically generates an optimized production `.zip` archive on every merge to the `main` branch.
* **No Node/NPM Required:** Pure PHP/JS workflow with no build steps needed.

## 📦 Installation

1. Navigate to the **Releases** section on the right side of this GitHub repository.
2. Download the latest `wp-blanco-popup.zip` asset.
3. Log in to your WordPress Dashboard.
4. Go to **Plugins** > **Add New Plugin** > **Upload Plugin**.
5. Choose the downloaded `.zip` file and click **Install Now**.
6. Once uploaded, click **Activate**.

---

## 💡 Usage

You can display and trigger the blank popup anywhere on your site using the methods below:

### 1. Shortcode
Insert the popup content anywhere inside your post or page editor:
```wordpress
[blanco_popup id="my-popup"]
   <h3>Your Popup Title</h3>
   <p>This is the blank content inside your custom popup.</p>
[/blanco_popup]
```

### 2. Triggering the Popup
To open the popup, add the class `open-blanco-popup` to any button, link, or menu item, and match the target ID:
```html
<a href="#my-popup" class="open-blanco-popup">Click here to open popup</a>
```

### 3. Theme Template (PHP)
If you want to hardcode the popup directly into your theme templates (e.g., `footer.php`):
```php
<?php 
echo do_shortcode('[blanco_popup id="footer-popup"]<p>Global Footer Notice</p>[/blanco_popup]'); 
?>
```

---

## 🛠️ For Developers: Contribution & Release Workflow

This repository uses **GitHub Actions** to automate production releases. To ensure code stability, pushing directly to the `main` branch is strictly prohibited. All updates must go through the formal review process.

### Branch & Deployment Policy

1. **Create a Feature Branch**  
   Never work directly on `main`. Create a descriptive feature or bugfix branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Update Code & Version Header**  
   Apply your code changes. If you are preparing a deployment, open `wp-blanco-popup.php` and bump the version number in the plugin header:
   ```php
   /**
    * Plugin Name: WP Blanco Popup
    * Version:     1.0.1  <-- Increment this for a new release
    */
   ```

3. **Commit and Push to your Branch**  
   Commit your work and push the feature branch to GitHub:
   ```bash
   git add .
   git commit -m "Add new feature and bump version to 1.0.1"
   git push origin feature/your-feature-name
   ```

4. **Open a Pull Request (PR)**  
   Go to GitHub and open a Pull Request from your feature branch into `main`.

5. **Review & Approval (Required)**  
   The changes must go through code review. The PR **requires explicit approval** from an authorized reviewer before it can be merged.

6. **Merge & Automated Release**  
   Once approved and merged into `main`, the GitHub workflow instantly triggers to:
   * Read the new version string directly from `wp-blanco-popup.php`.
   * Bundle the plugin files into a clean archive (excluding `.git`, `.github`, and dev utilities).
   * Create an official GitHub release tagged `v1.0.1` containing your ready-to-install `.zip`.

---

## 📂 Repository Structure

* `wp-blanco-popup.php` - Main plugin file containing header information and core functionality.
* `.github/workflows/release.yml` - CI/CD automated workflow file.
* `README.md` - Documentation and setup guide.
