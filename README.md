# ATAM - Axelerant Tech Assessment Module

This module was written as a tech assessment task while applying for Drupal developer position at Axelerant.

## Description
This module gives your site simple API key verification mechanism and provides endpoint to output pages in a JSON format.

## Installation
Download and enable as any standard Drupal module.

## Usage
After enabling:
1. Visit `/admin/config/system/site-information`; 
2. Fill *Site API Key* field with your key Update Configuration.
3. Make sure you have at least 1 node with content type `page` added.
4. Visit `/page_json/{YOUR_API_KEY}/{nid}` to see your page in JSON format.
