# Cricket Matches WordPress Plugin

A custom WordPress plugin for managing and displaying cricket match information with comprehensive meta fields and beautiful frontend display.

## Features

- **Custom Post Type**: Dedicated "Cricket Matches" post type
- **Rich Meta Fields**: Complete match information including:
  - Match image (featured image)
  - Series badge
  - Popular match flag
  - Team names (1 or 2 teams)
  - Match time
  - Win probability data
  - Prediction text
  - Betting statistics
  - Odds information
  - Custom bet button with URL
- **Frontend Display**: Beautiful, responsive match cards
- **Shortcode Support**: Easy integration with any page or post

## Installation

1. Upload the `cricket-matches` folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. **Sample Data**: 6 sample cricket matches will be automatically created on activation
4. Navigate to "Cricket Matches" in the admin menu to view or add more matches
5. **Quick Help**: Go to **Cricket Matches > How to Use** for shortcode examples and documentation

## Usage

### Sample Data Included

Upon activation, the plugin automatically creates **6 sample cricket matches** with complete data:

1. **দুমাই জুলফিকার ক্রিকেট বনাম** (আর্মিপ্রিমিয়ার ২০২৫) - Popular
2. **মিডনিশনার্স পিয়ান্সার বনাম** (আর্মিপ্রিমিয়ার ২০২৫)
3. **অস্ট্রেলিয়া বনাম ইংল্যান্ড** (টেস্ট সিরিজ) - Popular
4. **পাকিস্তান বনাম নিউজিল্যান্ড** (ODI সিরিজ)
5. **রাজস্থান রয়্যালস বনাম পাঞ্জাব কিংস** (আর্মিপ্রিমিয়ার ২০২৫) - Popular
6. **মুম্বাই ইন্ডিয়ানস জেনেসোয়াকশি বনাম** (আর্মিপ্রিমিয়ার ২০২৫)

All sample matches include:
- Series badges
- Popular flags (where applicable)
- Team names
- Match times
- Win probability predictions
- Betting statistics
- Odds information

**Note**: You'll need to add featured images manually to each match for complete display.

### Adding a Match

1. Go to **Cricket Matches > Add New** in WordPress admin
2. Enter the match title
3. Set a featured image (match image)
4. Fill in the match details in the "Match Details" meta box:
   - **Series Badge**: e.g., "আর্মিপ্রিমিয়ার ২০২৫"
   - **Popular**: Check if this is a popular match
   - **Team Name 1**: First team name
   - **Team Name 2**: Second team name (optional for "vs" format)
   - **Match Time**: e.g., "আজ সন্ধ্যা ৭:০০ PM"
   - **Win Probability Team**: Team with higher win chance
   - **Win Probability Percentage**: e.g., 65
   - **Prediction Text**: Match prediction details
   - **Total Bets**: e.g., "৩,৫০০+"
   - **Odds**: e.g., "1.85"
   - **Bet Button Text**: e.g., "এখনই বেট করুন →"
   - **Bet Button URL**: URL for betting link
5. Click "Publish"

### Displaying Matches

Use the `[cricket_matches]` shortcode to display matches on any page or post.

**Basic Usage:**
```
[cricket_matches]
```

**With Parameters:**
```
[cricket_matches limit="6" orderby="date" order="DESC"]
```

**Parameters:**
- `limit`: Number of matches to display (default: -1 for all)
- `orderby`: Sort by field (default: "date")
- `order`: Sort order "ASC" or "DESC" (default: "DESC")

**Examples:**
```
[cricket_matches limit="3"]
[cricket_matches limit="6" orderby="title" order="ASC"]
[cricket_matches orderby="date" order="DESC"]
```

### Template Integration

You can also display matches in your theme templates:

```php
<?php echo do_shortcode('[cricket_matches limit="6"]'); ?>
```

## Styling

The plugin includes responsive CSS styles that match the provided HTML design. The styles include:

- Responsive grid layout (3 columns on desktop, 2 on tablet, 1 on mobile)
- Hover effects on match cards
- Gradient backgrounds for prediction boxes
- Smooth transitions and animations
- Fully responsive design

## File Structure

```
cricket-matches/
├── cricket-matches.php    # Main plugin file
├── css/
│   └── style.css         # Frontend styles
└── README.md             # This file
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Customization

### Modifying Styles

Edit `wp-content/plugins/cricket-matches/css/style.css` to customize the appearance.

### Adding Custom Fields

Add new meta fields in the `cricket_matches_meta_box_callback()` function and update the save function accordingly.

### Template Override

The shortcode output is generated in the `cricket_matches_shortcode()` function. You can filter or modify the output as needed.

## Admin Help Page

The plugin includes a comprehensive help page accessible from WordPress admin:

**Location**: Cricket Matches > How to Use

**Features:**
- 🚀 Quick Start Guide
- 📝 5 Shortcode Examples with Copy Buttons
- ⚙️ Parameter Reference Table
- 📍 Step-by-step Usage Instructions
- 📦 Sample Data Information
- ✨ Features Overview
- 📞 Support Resources

All shortcode examples include one-click copy buttons for easy use!

## Support

For issues or feature requests, please contact the plugin developer.

**Quick Access:**
- In-plugin help: Cricket Matches > How to Use
- Documentation: README.md, ACTIVATION-GUIDE.md, SHORTCODE-GUIDE.md

## Changelog

### Version 1.0.0
- Initial release
- Custom post type for cricket matches
- Complete meta fields for match information
- Responsive frontend display
- Shortcode integration

## License

This plugin is proprietary software developed for NotOut.
