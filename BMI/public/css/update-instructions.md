# Instructions for Updating Blade Templates

## 1. Include CSS Files
Add this to your Blade templates to include the extracted CSS:

```blade
<x-css-include page="welcome" />
```

## 2. Remove Inline Styles
Replace inline styles with CSS classes:

Before:
```html
<div style="background-color: #f0f0f0; padding: 20px;">
```

After:
```html
<div class="bg-gray-100 p-5">
```

## 3. Remove Style Tags
Move content from <style> tags to external CSS files.

## 4. Update Class Names
Replace complex Tailwind classes with semantic class names:

Before:
```html
<div class="bg-white shadow-lg rounded-lg p-6 mb-4">
```

After:
```html
<div class="card">
```

## 5. Create Semantic CSS
Add semantic class names to your CSS files:

```css
.card {
    @apply bg-white shadow-lg rounded-lg p-6 mb-4;
}
```

## 6. Test Your Changes
After making changes, test your templates to ensure they still look correct.