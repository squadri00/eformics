#!/usr/bin/env python3
"""Push updated site files to the live Hostinger server over SSH (scp).
Fill in CONFIG below once, then run:  python deploy.py
Never pushes db-config.php / mail-config.php (live secrets stay on the server).
"""
import os
import subprocess
import sys

# ---- CONFIG — fill in from hPanel > Advanced > SSH Access ----
HOST = "srv1264.hstgr.io"
PORT = "65002"
USER = "u528385036"
REMOTE_DIR = "public_html"
LOCAL_DIR = r"D:\xampp\htdocs\ef"
# ----------------------------------------------------------------

INCLUDE = [
    "index.php", "about.php", "contact.php", "portfolio.php",
    "privacy-policy.php", "terms.php", "sitemap.php", "robots.txt",
    "partials", "assets", "images", "products", "services", "admin",
]

EXCLUDE = {
    "db-config.php", "db-config-live.php", "mail-config.php",
    "db-config.example.php", "mail-config.example.php",
    "mailcheck.php", "porttest.php", "efwebsite.zip", "script.txt",
    "295891", "sql", "vendor", ".git", ".gitignore",
    "composer.json", "composer.lock", "deploy.py",
}


def run(cmd):
    print("->", " ".join(cmd))
    if subprocess.run(cmd).returncode != 0:
        sys.exit(f"FAILED: {' '.join(cmd)}")


def main():
    os.chdir(LOCAL_DIR)
    for item in INCLUDE:
        if item in EXCLUDE or not os.path.exists(item):
            continue
        cmd = ["scp", "-P", PORT]
        if os.path.isdir(item):
            cmd.append("-r")
        cmd += [item, f"{USER}@{HOST}:{REMOTE_DIR}/"]
        run(cmd)
    print("\nDone. db-config.php and mail-config.php were NOT touched.")


if __name__ == "__main__":
    main()
