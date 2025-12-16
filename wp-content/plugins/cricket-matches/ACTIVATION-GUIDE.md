# Cricket Matches Plugin - Activation Guide

## Quick Start (3 Steps)

### Step 1: Activate Plugin
1. Go to WordPress Admin → **Plugins**
2. Find "**Cricket Matches**" in the list
3. Click **"Activate"**

### Step 2: Verify Sample Data
1. Go to **Cricket Matches** menu in WordPress admin
2. You should see **6 sample matches** automatically created
3. These matches contain all the data from your HTML template

### Step 3: Display Matches
1. Edit any page or post
2. Add this shortcode: `[cricket_matches]`
3. Save and view the page

**That's it!** Your cricket matches will display in a beautiful grid layout matching your HTML design.

---

## What Happens on Activation?

When you activate the plugin, it automatically:

✅ **Creates Custom Post Type**: "Cricket Matches" with admin menu
✅ **Registers Meta Fields**: All fields for match data (teams, odds, predictions, etc.)
✅ **Inserts 6 Sample Matches**: Pre-populated with data from your HTML
✅ **Loads Styles**: Responsive CSS matching your design
✅ **Registers Shortcode**: `[cricket_matches]` ready to use

---

## Sample Matches Created

The plugin creates these 6 matches automatically:

### Match 1: দুমাই জুলফিকার ক্রিকেট বনাম
- **Series**: আর্মিপ্রিমিয়ার ২০২৫
- **Popular**: Yes (🔥)
- **Time**: আজ সন্ধ্যা ৭:০০ PM
- **Win Probability**: দুমাই 65%
- **Bets**: ৩,৫০০+
- **Odds**: 1.85

### Match 2: মিডনিশনার্স পিয়ান্সার বনাম
- **Series**: আর্মিপ্রিমিয়ার ২০২৫
- **Time**: আজ রাত ১০:০০ PM
- **Win Probability**: সনকাতার 58%
- **Bets**: ১,৮০০+
- **Odds**: 2.10

### Match 3: অস্ট্রেলিয়া বনাম ইংল্যান্ড
- **Series**: টেস্ট সিরিজ
- **Popular**: Yes (🔥)
- **Time**: আজ সকাল ৯:০০ AM
- **Win Probability**: অস্ট্রেলিয়া 72%
- **Bets**: ৪,২০০+
- **Odds**: 1.65

### Match 4: পাকিস্তান বনাম নিউজিল্যান্ড
- **Series**: ODI সিরিজ
- **Time**: আজ বিকাল ৩:০০ PM
- **Win Probability**: পাকিস্তান 55%
- **Bets**: ২,৫০০+
- **Odds**: 1.95

### Match 5: রাজস্থান রয়্যালস বনাম পাঞ্জাব কিংস
- **Series**: আর্মিপ্রিমিয়ার ২০২৫
- **Popular**: Yes (🔥)
- **Time**: আজ সন্ধ্যা ৭:০০ PM
- **Win Probability**: রাজস্থান 61%
- **Bets**: ৩,০০০+
- **Odds**: 1.90

### Match 6: মুম্বাই ইন্ডিয়ানস জেনেসোয়াকশি বনাম
- **Series**: আর্মিপ্রিমিয়ার ২০২৫
- **Time**: কাল রাত ১০:০০ PM
- **Win Probability**: মুম্বাই 68%
- **Bets**: ২,৮০০+
- **Odds**: 1.75

---

## Next Steps After Activation

### 1. Add Featured Images (Optional)
1. Go to **Cricket Matches > All Matches**
2. Click on any match to edit
3. Set a **Featured Image** in the right sidebar
4. Save the match

### 2. Customize Matches
1. Edit any sample match to change data
2. Update team names, times, predictions, etc.
3. All fields are editable in the "Match Details" meta box

### 3. Add More Matches
1. Go to **Cricket Matches > Add New**
2. Enter match title
3. Fill in all match details
4. Set featured image
5. Publish

### 4. Display on Your Site
Add the shortcode to any page:

**Homepage Example**:
```
[cricket_matches limit="6"]
```

**All Matches Page**:
```
[cricket_matches]
```

**Sidebar (3 matches)**:
```
[cricket_matches limit="3"]
```

---

## Shortcode Usage

### Basic Display
```
[cricket_matches]
```
Shows all matches

### With Limit
```
[cricket_matches limit="6"]
```
Shows 6 most recent matches

### Custom Sorting
```
[cricket_matches limit="10" orderby="date" order="DESC"]
```
Shows 10 matches, newest first

---

## File Structure

```
wp-content/plugins/cricket-matches/
├── cricket-matches.php          (Main plugin - 27KB)
├── css/
│   └── style.css               (Responsive styles - 4KB)
├── README.md                   (General documentation)
├── SHORTCODE-GUIDE.md          (Shortcode reference)
└── ACTIVATION-GUIDE.md         (This file)
```

---

## Verify Installation

### Check Plugin Status
- **Admin Menu**: Look for "Cricket Matches" in WordPress sidebar
- **Sample Data**: 6 matches should appear in Cricket Matches > All Matches
- **Shortcode**: `[cricket_matches]` should display matches on frontend

### Test Frontend Display
1. Create a test page
2. Add shortcode: `[cricket_matches limit="3"]`
3. Preview the page
4. Verify:
   - ✅ Matches display in grid layout
   - ✅ Cards have hover effects
   - ✅ Responsive design works
   - ✅ All match data shows correctly

---

## Troubleshooting

### "Sample data not appearing"
**Solution**: Deactivate and reactivate the plugin

### "Shortcode displays as text"
**Solution**: Verify you're using `[cricket_matches]` not `{cricket_matches}`

### "Styling looks broken"
**Solution**:
1. Clear browser cache
2. Check if `style.css` file exists in plugin folder
3. Try different theme

### "Featured images missing"
**Note**: Sample matches don't include images automatically. Add them manually:
1. Edit each match
2. Set Featured Image
3. Save

---

## Important Notes

⚠️ **Sample Data**: Created only once on first activation
⚠️ **Deactivation**: Sample matches remain (not deleted)
⚠️ **Reactivation**: Won't duplicate sample data
⚠️ **Images**: Must be added manually to sample matches

---

## Need Help?

### Resources
- `README.md` - Full plugin documentation
- `SHORTCODE-GUIDE.md` - Complete shortcode reference

### Common Tasks
- **Edit Match**: Cricket Matches > All Matches > Click match title
- **Delete Match**: Cricket Matches > All Matches > Trash
- **Add Match**: Cricket Matches > Add New
- **Shortcode**: Copy `[cricket_matches]` to any page/post

---

## Plugin Information

- **Version**: 1.0.0
- **Post Type**: `cricket_match`
- **Shortcode**: `[cricket_matches]`
- **CSS**: Auto-loaded on frontend
- **Sample Matches**: 6 pre-configured

---

**Ready to use!** 🎉

Add the shortcode to your homepage and enjoy your cricket match display!
