# Dope Carousel for Elementor

Custom Elementor carousel widget plugin powered by Swiper.

## Features
- Single widget: **Dope Carousel**
- Layouts: Slider, Single Row Carousel, Double Row Carousel
- Slide styles: Slide, Fade, Ticker
- Ticker direction control (left-to-right / right-to-left)
- Dual content sources:
  - Manual mode: repeater-based slides (image, title, description, button text, link)
  - Gallery mode: bulk image selection from gallery control
- Gallery mode reads per-image metadata from Media Library:
  - Title (attachment title)
  - Caption
  - Description
  - Carousel button text (custom media field)
  - Carousel button link (custom media field)
- Gallery mode visibility toggles:
  - Show/Hide title
  - Show/Hide description
  - Show/Hide button
- Double-row ticker supports alternate row movement (top and bottom rows move in opposite directions)
- Style controls for container, card, image, title, description, button, arrows, and pagination
- Fade fallback behavior for unsupported layouts (auto-fallback to Slide)

## Installation
1. Place this plugin folder in `wp-content/plugins/`.
2. Activate **Dope Carousel for Elementor**.
3. Open Elementor and add the **Dope Carousel** widget from the General category.

## Requirements
- WordPress
- Elementor
- PHP 7.4+

## Notes
- Fade effect works only with the Slider layout. If Fade is selected with Single Row or Double Row, the frontend automatically uses Slide.
- In Gallery mode, Dope Carousel uses Media Library metadata as the content source of truth.
- If Gallery mode has no selected images, the widget falls back to existing manual repeater slides for backward compatibility.
- You can edit per-image button fields from Media Library attachment details:
  - `Carousel Button Text`
  - `Carousel Button Link`
- Alternate direction movement is applied for `Layout = Double Row` + `Slide Style = Ticker`.
- Manual mode behavior remains unchanged.
