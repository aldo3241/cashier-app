# 🎨 Inspizo Spiritosanto Cashier System - Color Palette

## Professional Color System

This document defines the official color palette for the Inspizo Spiritosanto Cashier System. All UI components should use these colors to maintain consistency across the application.

---

## Primary Brand Colors

### **Blue (Primary)**
- **Purpose**: Main brand color, primary actions, info messages
- **Colors**:
  - `#2563eb` (Blue 600) - Primary buttons, links
  - `#1e40af` (Blue 700) - Hover states, active states
  - `#3b82f6` (Blue 500) - Lighter variant
  - `#eff6ff` (Blue 50) - Background tints
  - `#dbeafe` (Blue 100) - Light backgrounds

**Usage**:
- Primary buttons
- Links
- "All Sales" stat cards
- Header icons
- Active navigation items

---

### **Purple (Secondary)**
- **Purpose**: Secondary brand color, complementary actions
- **Colors**:
  - `#8b5cf6` (Purple 500) - Secondary buttons
  - `#7c3aed` (Purple 600) - Hover states
  - `#f5f3ff` (Purple 50) - Background tints
  - `#ede9fe` (Purple 100) - Light backgrounds

**Usage**:
- "All Cashiers Sales" buttons
- "Total Products" stat cards
- Secondary actions
- Data visualization accents

---

## Success/Positive Colors

### **Emerald (Success)**
- **Purpose**: Positive actions, revenue, completed states
- **Colors**:
  - `#10b981` (Emerald 500) - Success messages, positive values
  - `#059669` (Emerald 600) - Hover states
  - `#34d399` (Emerald 400) - Lighter variant
  - `#ecfdf5` (Emerald 50) - Background tints
  - `#d1fae5` (Emerald 100) - Light backgrounds

**Usage**:
- "My Sales" buttons and cards
- Revenue displays
- Success messages
- Completed transactions
- Positive metrics

---

## Warning Colors

### **Amber (Warning)**
- **Purpose**: Warnings, cautionary messages
- **Colors**:
  - `#f59e0b` (Amber 500) - Warning messages
  - `#d97706` (Amber 600) - Hover states
  - `#fffbeb` (Amber 50) - Background tints
  - `#fef3c7` (Amber 100) - Light backgrounds

**Usage**:
- Warning messages
- Low stock warnings (secondary)
- Cautionary indicators

---

## Danger/Error Colors

### **Red (Danger)**
- **Purpose**: Errors, critical alerts, destructive actions
- **Colors**:
  - `#ef4444` (Red 500) - Error messages, critical alerts
  - `#dc2626` (Red 600) - Hover states for destructive actions
  - `#fef2f2` (Red 50) - Background tints
  - `#fee2e2` (Red 100) - Light backgrounds

**Usage**:
- Low stock alerts
- Error messages
- Delete/cancel buttons
- Critical warnings
- Refunded transactions

---

## Info/Accent Colors

### **Cyan (Info)**
- **Purpose**: Informational messages, highlights
- **Colors**:
  - `#06b6d4` (Cyan 500) - Info messages
  - `#0891b2` (Cyan 600) - Hover states
  - `#ecfeff` (Cyan 50) - Background tints
  - `#cffafe` (Cyan 100) - Light backgrounds

**Usage**:
- Information messages
- Tooltips
- Help indicators

---

## Neutral Colors

### **Gray Scale**
- **Purpose**: Text, borders, backgrounds, UI structure
- **Colors**:
  - `#f9fafb` (Gray 50) - Light backgrounds
  - `#f3f4f6` (Gray 100) - Card backgrounds, hover states
  - `#e5e7eb` (Gray 200) - Borders, dividers
  - `#d1d5db` (Gray 300) - Input borders, disabled states
  - `#4b5563` (Gray 600) - Secondary text
  - `#374151` (Gray 700) - Body text
  - `#1f2937` (Gray 800) - Headings
  - `#111827` (Gray 900) - Dark text, high emphasis

**Usage**:
- Text hierarchy
- Borders and dividers
- Background layers
- Disabled states

---

## Gradient Combinations

### **Brand Gradients**

1. **Primary Gradient**
   - `from-blue-600 to-purple-500`
   - Usage: Main logo, feature cards

2. **Success Gradient**
   - `from-emerald-400 to-blue-500`
   - Usage: "My Sales" buttons, profile avatars

3. **Secondary Gradient**
   - `from-purple-500 to-blue-500`
   - Usage: "All Sales" buttons, secondary features

4. **DataTable Gradients**
   - My Sales: `linear-gradient(135deg, #10b981, #3b82f6)`
   - All Sales: `linear-gradient(135deg, #8b5cf6, #3b82f6)`

---

## Component-Specific Usage

### **Buttons**

```html
<!-- Primary Action -->
<button class="bg-blue-600 hover:bg-blue-700">

<!-- Success Action (My Sales) -->
<button class="bg-emerald-500 hover:bg-emerald-600">

<!-- Secondary Action (All Sales) -->
<button class="bg-purple-500 hover:bg-purple-600">

<!-- Danger Action -->
<button class="bg-red-500 hover:bg-red-600">
```

### **Stat Cards**

```html
<!-- Success (Revenue) -->
<div class="w-12 h-12 bg-emerald-100">
    <svg class="text-emerald-600">

<!-- Primary (Sales) -->
<div class="w-12 h-12 bg-blue-100">
    <svg class="text-blue-600">

<!-- Secondary (Products) -->
<div class="w-12 h-12 bg-purple-100">
    <svg class="text-purple-600">

<!-- Danger (Alerts) -->
<div class="w-12 h-12 bg-red-100">
    <svg class="text-red-500">
```

### **Status Badges**

```css
/* Completed */
.status-completed {
    background-color: #dcfce7; /* emerald-100 */
    color: #166534; /* emerald-800 */
}

/* Refunded */
.status-refunded {
    background-color: #fee2e2; /* red-100 */
    color: #991b1b; /* red-800 */
}

/* Pending */
.status-pending {
    background-color: #fef3c7; /* amber-100 */
    color: #92400e; /* amber-800 */
}
```

---

## Accessibility Guidelines

### **Contrast Ratios**
- **Normal Text**: Minimum 4.5:1 contrast ratio
- **Large Text (18px+)**: Minimum 3:1 contrast ratio
- **UI Components**: Minimum 3:1 contrast ratio

### **Color Blindness Considerations**
- Never rely on color alone to convey information
- Use icons, text labels, and patterns alongside colors
- Test with color blindness simulators

### **Tested Combinations**
✅ **Good Contrast**:
- White text on Blue 600+
- White text on Emerald 500+
- White text on Purple 500+
- White text on Red 500+
- Gray 700+ text on white backgrounds

---

## Design Tokens (CSS Variables)

```css
:root {
    /* Primary Brand Colors */
    --color-primary: #2563eb;        /* Blue 600 */
    --color-primary-dark: #1e40af;   /* Blue 700 */
    --color-primary-light: #3b82f6;  /* Blue 500 */
    
    /* Secondary Colors */
    --color-secondary: #8b5cf6;      /* Purple 500 */
    --color-secondary-dark: #7c3aed; /* Purple 600 */
    
    /* Success/Positive */
    --color-success: #10b981;        /* Emerald 500 */
    --color-success-dark: #059669;   /* Emerald 600 */
    --color-success-light: #34d399;  /* Emerald 400 */
    
    /* Warning */
    --color-warning: #f59e0b;        /* Amber 500 */
    --color-warning-dark: #d97706;   /* Amber 600 */
    
    /* Danger/Error */
    --color-danger: #ef4444;         /* Red 500 */
    --color-danger-dark: #dc2626;    /* Red 600 */
    
    /* Info */
    --color-info: #06b6d4;          /* Cyan 500 */
    --color-info-dark: #0891b2;     /* Cyan 600 */
    
    /* Neutral Grays */
    --color-gray-50: #f9fafb;
    --color-gray-100: #f3f4f6;
    --color-gray-200: #e5e7eb;
    --color-gray-300: #d1d5db;
    --color-gray-600: #4b5563;
    --color-gray-700: #374151;
    --color-gray-800: #1f2937;
    --color-gray-900: #111827;
}
```

---

## Color Psychology

- **Blue**: Trust, professionalism, stability - Perfect for financial applications
- **Emerald/Green**: Growth, success, money - Ideal for revenue and sales
- **Purple**: Creativity, luxury, quality - Good for premium features
- **Red**: Urgency, attention, danger - Appropriate for alerts and warnings

---

## Maintenance Notes

- All colors are based on Tailwind CSS default color palette
- Color names use Tailwind's naming convention (e.g., `emerald-500`, `blue-600`)
- When adding new colors, maintain contrast ratios and accessibility standards
- Test all color changes across different screens and lighting conditions

---

**Last Updated**: October 11, 2025
**Version**: 1.0
**Maintained By**: Development Team

