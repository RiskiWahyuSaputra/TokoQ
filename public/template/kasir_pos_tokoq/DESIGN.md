---
name: TokoQ Design System
colors:
  surface: '#f8fbea'
  surface-dim: '#d9dccb'
  surface-bright: '#f8fbea'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f5e4'
  surface-container: '#edefdf'
  surface-container-high: '#e7ead9'
  surface-container-highest: '#e1e4d4'
  on-surface: '#191d13'
  on-surface-variant: '#45483d'
  inverse-surface: '#2e3227'
  inverse-on-surface: '#f0f2e2'
  outline: '#75786b'
  outline-variant: '#c5c8b9'
  surface-tint: '#51652e'
  primary: '#40521d'
  on-primary: '#ffffff'
  primary-container: '#576b33'
  on-primary-container: '#d3eba5'
  inverse-primary: '#b8cf8c'
  secondary: '#55633d'
  on-secondary: '#ffffff'
  secondary-container: '#d5e6b6'
  on-secondary-container: '#596841'
  tertiary: '#445122'
  on-tertiary: '#ffffff'
  tertiary-container: '#5c6938'
  on-tertiary-container: '#d9e8aa'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d3eba6'
  primary-fixed-dim: '#b8cf8c'
  on-primary-fixed: '#131f00'
  on-primary-fixed-variant: '#3a4d18'
  secondary-fixed: '#d8e9b9'
  secondary-fixed-dim: '#bccd9e'
  on-secondary-fixed: '#131f02'
  on-secondary-fixed-variant: '#3d4b28'
  tertiary-fixed: '#dae9ac'
  tertiary-fixed-dim: '#becd92'
  on-tertiary-fixed: '#161f00'
  on-tertiary-fixed-variant: '#3f4b1d'
  background: '#f8fbea'
  on-background: '#191d13'
  surface-variant: '#e1e4d4'
typography:
  h1:
    fontSize: 40px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  h2:
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.3'
    letterSpacing: -0.01em
  h3:
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-caps:
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: 0.05em
  h1-mobile:
    fontSize: 28px
    fontWeight: '700'
    lineHeight: '1.2'
  h2-mobile:
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.3'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-padding: 32px
  gutter: 24px
  card-gap: 24px
  section-margin: 48px
---

## Brand & Style

The design system is anchored in the "Local Business Digital Twin" concept, bridging the gap between physical Indonesian MSME storefronts and a sophisticated digital command center. The brand personality is **Nurturing, Premium, and Grounded**. It avoids the sterile "tech-blue" look in favor of an organic "Matcha Packaging" aesthetic—evoking the tactile quality of high-end, locally sourced craft paper and natural ingredients.

The visual style is a hybrid of **Minimalism** and **Tactile Modernism**. It prioritizes high-quality typography and generous whitespace, but utilizes soft, layered depth to make digital components feel like physical objects on a clean wooden desk. This approach reduces the "digital fatigue" often felt by small business owners managing complex inventory and sales data.

## Colors

The palette is derived from the varying stages of matcha and olive—ranging from sun-bleached sage to deep, concentrated chlorophyll. 

- **Primary & Secondary:** Use Primary Dark Matcha for critical actions (Submit, Save) and Soft Matcha for high-surface-area backgrounds to maintain a "natural" feel without overwhelming the eye.
- **Background Strategy:** Instead of a pure white or clinical grey background, this design system uses a Warm Grey Matcha (#CDD0C0). This reduces screen glare and provides a sophisticated, paper-like canvas for the Main Surface (#FFFFFF) cards to sit upon.
- **Semantic Logic:** Status colors are slightly desaturated to remain harmonious with the organic theme. Success is represented by "Fresh Olive" rather than a neon green, reinforcing the growth and health of the business.

## Typography

This design system utilizes **Plus Jakarta Sans** for its friendly, modern, and highly legible curves. The typeface's Indonesian heritage aligns perfectly with the target audience of local MSMEs.

- **Headlines:** Use Bold weights with tight letter spacing for a premium "editorial" look.
- **Body:** Standard body text uses the 16px (Medium) size to ensure accessibility for business owners who may be checking their dashboard in varying lighting conditions (e.g., inside a shop or warehouse).
- **Indonesian Language Support:** Ensure line heights are generous (1.5 - 1.6) to accommodate longer Indonesian words and prevent text dense "blocks" that are difficult to scan.

## Layout & Spacing

The layout follows a **Fixed-Fluid Hybrid** model. The sidebar remains fixed, while the content area uses a fluid grid with defined max-widths to prevent line lengths from becoming unreadable on ultra-wide monitors.

- **Spaciousness:** Use a base-8 rhythm. All margins and paddings must be multiples of 8px.
- **Dashboard Grid:** A 12-column grid is used for desktop. For the "Digital Twin" feel, cards should be large and expansive. Small "widgets" should be avoided in favor of "Business Insight Cards" that span at least 4 columns.
- **Mobile Reflow:** On mobile devices, the 32px container padding reduces to 16px. All multi-column cards stack vertically to maintain the 16-24px corner radius integrity.

## Elevation & Depth

Hierarchy is established through **Ambient Shadows** and **Tonal Layering**. Unlike standard SaaS tools that use stark drop shadows, this design system uses soft, diffused shadows with a slight Olive tint (#49592A at 8-12% opacity) to make elements feel like they are floating just above a paper surface.

- **Level 0 (Floor):** Warm Grey Matcha background (#CDD0C0).
- **Level 1 (Cards/Surfaces):** Main Surface White (#FFFFFF) with a 2px Subtle Border (#DDE3D2).
- **Level 2 (Active Elements):** For modals or hovered cards, increase the shadow spread and add a 1px border of Accent Olive Sage (#86945E) to simulate light catching the edge.

## Shapes

The shape language is significantly rounded, moving away from "corporate sharp" to "consumer soft." 

- **Primary Cards:** Must use a 24px radius to feel welcoming and modern.
- **Buttons & Inputs:** Use a 16px radius for a consistent, "pill-lite" look.
- **Icon Containers:** Use "Squircle" shapes rather than perfect circles to maintain the premium packaging aesthetic.

## Components

### Buttons
- **Primary:** Background #576B33, Text #FFFFFF. High-contrast, no shadow on rest, slight upward "lift" (larger shadow) on hover.
- **Secondary:** Background #C3D4A5, Text #1F2A17. Used for less critical actions like "Lihat Detail."

### Insight Cards
Large containers for data. They should include a subtle 4px top-accent border using the AI Insight color (#86945E) to indicate "Smart" data generated by the platform.

### Form Inputs
Field labels should always be visible (never placeholder-only) in Secondary Text (#5E6655). Input backgrounds are white with a 1px border (#DDE3D2) that transitions to Primary Dark Matcha on focus.

### Status Chips
- **Selesai (Success):** Soft Olive background with deep green text.
- **Proses (Warning):** Soft Amber background with dark brown text.
- **Batal (Danger):** Soft Red/Terracotta background with dark red text.

### Navigation (Sidebar)
The sidebar uses a semi-transparent version of the Background color to create a "glass-paper" effect. Active states are indicated by a vertical 4px rounded bar in Primary Dark Matcha and a subtle color shift of the icon.