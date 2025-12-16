# Cricket Matches Shortcode Guide

## Basic Usage

The `[cricket_matches]` shortcode displays your cricket matches in a beautiful, responsive grid layout that matches your provided HTML design.

## Shortcode Examples

### Display All Matches
```
[cricket_matches]
```
Shows all published cricket matches in descending order by date.

### Display Limited Matches
```
[cricket_matches limit="6"]
```
Shows only 6 most recent matches (perfect for homepage).

### Custom Sorting
```
[cricket_matches limit="3" orderby="title" order="ASC"]
```
Shows 3 matches sorted alphabetically by title.

### Recent Matches
```
[cricket_matches limit="10" orderby="date" order="DESC"]
```
Shows 10 most recent matches.

## Shortcode Parameters

| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `limit` | Number of matches to display | `-1` (all) | Any number (e.g., `3`, `6`, `12`) |
| `orderby` | Sort matches by | `date` | `date`, `title`, `ID`, `modified` |
| `order` | Sort direction | `DESC` | `ASC` (ascending), `DESC` (descending) |

---

## Paginated Version: `[cricket_matches_paged]`

The `[cricket_matches_paged]` shortcode displays cricket matches with **pagination support** and **column control**.

### Display 6 Matches in 3 Columns with Pagination
```
[cricket_matches_paged limit="6" columns="3"]
```
Perfect for archive pages with many matches.

### Display 9 Matches in 3 Columns
```
[cricket_matches_paged limit="9" columns="3"]
```
Shows 9 matches per page in 3 columns.

### Display 8 Matches in 4 Columns
```
[cricket_matches_paged limit="8" columns="4"]
```
Maximum column layout for wide displays.

### Display 4 Matches in 2 Columns
```
[cricket_matches_paged limit="4" columns="2"]
```
Compact layout for sidebars or narrow sections.

### Display 6 Matches in 1 Column (List View)
```
[cricket_matches_paged limit="6" columns="1"]
```
Full-width list view.

## Paginated Shortcode Parameters

| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `limit` | Matches per page | `6` | Any number (e.g., `6`, `9`, `12`) |
| `columns` | Number of columns | `3` | `1`, `2`, `3`, `4` |
| `orderby` | Sort matches by | `date` | `date`, `title`, `ID`, `modified` |
| `order` | Sort direction | `DESC` | `ASC`, `DESC` |

### Pagination Features
- ✅ Bengali numbers (০, ১, ২, ৩...)
- ✅ Bengali navigation (পূর্ববর্তী, পরবর্তী)
- ✅ Professional design matching blog pagination
- ✅ Responsive behavior on all devices

### Responsive Column Behavior
- **Desktop (1025px+)**: Shows specified columns (1-4)
- **Tablet (769px - 1024px)**: Max 2 columns
- **Mobile (< 768px)**: Always 1 column

## Usage in Pages/Posts

### WordPress Editor (Gutenberg)
1. Add a "Shortcode" block
2. Enter: `[cricket_matches limit="6"]`
3. Preview or Publish

### Classic Editor
1. Switch to "Text" mode
2. Add: `[cricket_matches limit="6"]`
3. Save

### Page Builders (Elementor, etc.)
1. Add a "Shortcode" widget
2. Enter: `[cricket_matches limit="6"]`
3. Save

## Usage in Theme Templates

### In PHP Files
```php
<?php echo do_shortcode('[cricket_matches limit="6"]'); ?>
```

### Example: Homepage Template
```php
<section class="cricket-matches-section">
    <h2>আসন্ন ম্যাচসমূহ</h2>
    <?php echo do_shortcode('[cricket_matches limit="6"]'); ?>
</section>
```

### Example: Sidebar Widget
```php
<div class="sidebar-matches">
    <h3>জনপ্রিয় ম্যাচ</h3>
    <?php echo do_shortcode('[cricket_matches limit="3"]'); ?>
</div>
```

## HTML Output Structure

The shortcode generates the following HTML structure (matching your design):

```html
<div class="cricket-container-match">
    <div class="container">
        <div class="matches-grid">
            <div class="match-card">
                <div class="image-container">
                    <div class="image-box">
                        <img class="blog-image" ... />
                    </div>
                    <div class="image-overlay">
                        <span class="badge badge-series">...</span>
                        <span class="badge badge-popular">...</span>
                    </div>
                </div>
                <div class="match-content">
                    <div class="match-teams">...</div>
                    <div class="match-time">...</div>
                    <div class="prediction-box">...</div>
                    <div class="match-stats">...</div>
                    <button class="bet-button">...</button>
                </div>
            </div>
            <!-- More match cards... -->
        </div>
    </div>
</div>
```

## Responsive Behavior

The shortcode automatically adapts to different screen sizes:

- **Desktop (1025px+)**: 3 columns
- **Tablet (769px - 1024px)**: 2 columns
- **Mobile (< 768px)**: 1 column

All styles are included automatically - no additional CSS needed!

## Customization

### Custom CSS Override
Add custom styles in your theme's `style.css`:

```css
/* Change card spacing */
.matches-grid {
    gap: 30px;
}

/* Change button color */
.bet-button {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

/* Modify card hover effect */
.match-card:hover {
    transform: translateY(-8px);
}
```

### Filter Matches by Custom Query
You can create custom variations by modifying the shortcode attributes:

```php
// Show only popular matches (requires custom code)
// Show matches from specific series (requires custom code)
```

## Troubleshooting

### Matches Not Displaying
1. Verify plugin is activated
2. Check that matches are published (not draft)
3. Verify shortcode syntax is correct

### Styling Issues
1. Clear browser cache
2. Check theme compatibility
3. Verify CSS file is loading

### Images Not Showing
1. Ensure featured images are set for each match
2. Check image file permissions
3. Verify image URLs are correct

## Performance Tips

1. **Limit Matches**: Use `limit` parameter to show fewer matches for faster loading
2. **Image Optimization**: Compress featured images before uploading
3. **Caching**: Use a caching plugin for better performance

## Support

For issues or customization needs, contact the plugin developer.
