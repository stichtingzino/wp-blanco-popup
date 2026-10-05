# WP Blanco Popup

A lightweight WordPress plugin designed to load specific pages inside a clean, borderless popup window by stripping away the default theme header, footer, and sidebars using a URL parameter.

## 🚀 Features
* **Theme Stripper (Blank Mode):** Automatically intercepts requests with `?popup=true` and serves a clean, core-only HTML wrapper without theme headers or footers.
* **Centered Popup Windows:** Includes a helper JavaScript function to trigger perfectly centered standalone browser windows with hidden navigation toolbars.
* **Automated Releases:** Generates an optimized production `.zip` archive on every merge to the `main` branch via GitHub Actions.

## 📦 Installation

1. Navigate to the **Releases** section on the right side of this GitHub repository.
2. Download the latest `wp-blanco-popup.zip` asset.
3. Log in to your WordPress Dashboard.
4. Go to **Plugins** > **Add New Plugin** > **Upload Plugin**.
5. Choose the downloaded `.zip` file and click **Install Now**.
6. Once uploaded, click **Activate**.

---

## 💡 Usage

To open any WordPress page inside the blank popup window, you can use the built-in JavaScript function `openWordPressPopup`.

### 1. Triggering via HTML Links
Add an `onclick` event to any link or button. Pass the event and the destination URL into the function:

```html
<a href="https://://yourwebsite.com" 
   onclick="openWordPressPopup(event, this.href, 800, 600);">
   Open Blank Page
</a>
```
*The script will automatically append `?popup=true` to the URL, center the window on the user's screen, and hide browser toolbars/menus.*

### 2. Manual URL Access
If you want to view the stripped-down, blanco version of a page inside a regular browser tab without opening a new window, simply append the parameter manually to your URL:
```text
https://://yourwebsite.com
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
   * Bundle the plugin files into a clean archive using standard `zip` utilities (excluding `.git` and `.github`).
   * Create an official GitHub release tagged `v1.0.1` containing your ready-to-install `.zip`.

---

## 📂 Repository Structure

* `wp-blanco-popup.php` - Main plugin file containing header information and backend hooks.
* `js/popup.js` - Central JavaScript function handling window sizing and instantiation.
* `.github/workflows/release.yml` - CI/CD automated workflow file.
* `README.md` - Documentation and setup guide.
