#!/bin/bash

# Create clean production ZIP
echo "Creating clean production ZIP..."

# Remove old ZIP if exists
rm -f terraassess-production-clean.zip

# Create ZIP with only necessary files
zip -r terraassess-production-clean.zip \
    app/ \
    bootstrap/ \
    config/ \
    database/ \
    lang/ \
    public/ \
    resources/ \
    routes/ \
    storage/ \
    vendor/ \
    artisan \
    composer.json \
    composer.lock \
    .env.production \
    .htaccess.production \
    -x \
    "*.log" \
    "*.tmp" \
    "*.cache" \
    "storage/logs/*" \
    "storage/framework/cache/*" \
    "storage/framework/sessions/*" \
    "storage/framework/views/*" \
    "storage/app/*" \
    "node_modules/*" \
    "tests/*" \
    "*.md" \
    "*.txt" \
    "*.sh" \
    ".git/*" \
    ".gitignore" \
    "*.zip" \
    "*.sqlite" \
    "database.sqlite" \
    "storage/debugbar/*"

echo "Clean production ZIP created: terraassess-production-clean.zip"
echo "Size: $(du -h terraassess-production-clean.zip | cut -f1)"
