# Manual Testing Guide

This document provides comprehensive manual testing procedures for the ArtisanPack UI Livewire Starter Kit. It covers installation testing, UI component verification, user management workflow testing, and CMS Framework compatibility testing.

## Table of Contents

1. [Installation Testing Procedures](#installation-testing-procedures)
2. [UI Component Verification](#ui-component-verification)
3. [User Management Workflow Testing](#user-management-workflow-testing)
4. [CMS Framework Compatibility Testing](#cms-framework-compatibility-testing)

## Installation Testing Procedures

### Standard Installation Testing

1. **Clean Environment Setup**
   - Create a new directory for testing
   - Ensure PHP 8.1+ is installed
   - Ensure Composer is installed
   - Ensure Node.js and npm are installed

2. **Installation Command**
   - Run the following command:
     ```bash
     composer create-project artisanpack-ui/livewire-starter-kit test-project
     ```
   - Verify the command completes without errors

3. **Interactive Prompts Testing**
   - Test with default options (answer "no" to all optional packages)
   - Verify the installation completes successfully
   - Check that the expected directory structure is created
   - Check that the expected files are created

4. **Optional Packages Testing**
   - Test with all optional packages (answer "yes" to all optional packages)
   - Verify the installation completes successfully
   - Check that the optional packages are installed
   - Check that the expected directory structure is created
   - Check that the expected files are created

5. **Custom Paths Testing**
   - Test with custom paths for key directories
   - Verify the installation completes successfully
   - Check that the custom directories are created
   - Check that the expected files are created in the custom directories

6. **UI Customization Testing**
   - Test with custom UI colors
   - Verify the installation completes successfully
   - Check that the CSS is generated with the custom colors
   - Test with dark mode enabled
   - Verify the CSS includes dark mode styles
   - Test with custom font family
   - Verify the CSS includes the custom font family
   - Test with custom font scaling
   - Verify the CSS includes the scaled font sizes

7. **Error Handling Testing**
   - Test with invalid inputs
   - Verify the installation script provides helpful error messages
   - Test with network issues (e.g., disconnect from the internet)
   - Verify the installation script handles network errors gracefully
   - Test with permission issues (e.g., read-only directory)
   - Verify the installation script handles permission errors gracefully

### CMS Framework Installation Testing

1. **CMS Framework Installation**
   - Run the installation with CMS Framework (answer "yes" to CMS Framework)
   - Verify the installation completes successfully
   - Check that the CMS Framework package is installed
   - Check that the User model and migrations are removed
   - Check that the CMS-compatible views are copied

2. **CMS Framework Configuration Testing**
   - Check that the CMS Framework configuration is created
   - Verify the configuration includes the expected settings
   - Test modifying the configuration
   - Verify the changes take effect

3. **CMS Framework Database Testing**
   - Run the migrations
   - Verify the migrations complete successfully
   - Check that the expected tables are created
   - Seed the database with initial data
   - Verify the seeding completes successfully
   - Check that the expected data is created

## UI Component Verification

### Basic Components Testing

1. **Button Component**
   - Verify the button component renders correctly
   - Test different button variants (primary, secondary, danger, etc.)
   - Test different button sizes (sm, md, lg)
   - Test disabled state
   - Test with icons
   - Test with loading state

2. **Input Component**
   - Verify the input component renders correctly
   - Test different input types (text, email, password, etc.)
   - Test validation states (error, success)
   - Test disabled state
   - Test with prefix and suffix
   - Test with label and help text

3. **Checkbox Component**
   - Verify the checkbox component renders correctly
   - Test checked and unchecked states
   - Test disabled state
   - Test with label
   - Test with validation

4. **Radio Component**
   - Verify the radio component renders correctly
   - Test selected and unselected states
   - Test disabled state
   - Test with label
   - Test with validation

5. **Select Component**
   - Verify the select component renders correctly
   - Test with different options
   - Test multiple selection
   - Test disabled state
   - Test with label and help text
   - Test with validation

6. **Textarea Component**
   - Verify the textarea component renders correctly
   - Test with different sizes
   - Test with auto-resize
   - Test disabled state
   - Test with label and help text
   - Test with validation

7. **Alert Component**
   - Verify the alert component renders correctly
   - Test different alert types (info, success, warning, error)
   - Test with icons
   - Test with dismiss button
   - Test with title and description

8. **Card Component**
   - Verify the card component renders correctly
   - Test with header and footer
   - Test with different content
   - Test with actions
   - Test with images

9. **Modal Component**
   - Verify the modal component renders correctly
   - Test opening and closing
   - Test with different sizes
   - Test with header and footer
   - Test with form elements
   - Test with actions

10. **Dropdown Component**
    - Verify the dropdown component renders correctly
    - Test opening and closing
    - Test with different items
    - Test with dividers
    - Test with icons
    - Test with nested dropdowns

### Dark Mode Testing

1. **Dark Mode Toggle**
   - Verify the dark mode toggle works correctly
   - Test switching between light and dark modes
   - Verify the UI updates correctly when switching modes

2. **Component Appearance in Dark Mode**
   - Verify all components render correctly in dark mode
   - Check color contrast in dark mode
   - Check readability in dark mode
   - Check hover and focus states in dark mode

3. **System Preference Detection**
   - Test with system preference set to light mode
   - Test with system preference set to dark mode
   - Verify the UI updates correctly when system preference changes

### Responsive Design Testing

1. **Mobile View Testing**
   - Test on small screens (320px - 639px)
   - Verify all components render correctly
   - Check navigation and menus
   - Check form elements
   - Check spacing and alignment

2. **Tablet View Testing**
   - Test on medium screens (640px - 1023px)
   - Verify all components render correctly
   - Check navigation and menus
   - Check form elements
   - Check spacing and alignment

3. **Desktop View Testing**
   - Test on large screens (1024px+)
   - Verify all components render correctly
   - Check navigation and menus
   - Check form elements
   - Check spacing and alignment

### Accessibility Testing

1. **Keyboard Navigation**
   - Test tabbing through all interactive elements
   - Verify focus states are visible
   - Test keyboard shortcuts
   - Test form submission with keyboard

2. **Screen Reader Testing**
   - Test with VoiceOver (macOS)
   - Test with NVDA or JAWS (Windows)
   - Verify all elements have appropriate ARIA attributes
   - Verify all elements have appropriate labels

3. **Color Contrast Testing**
   - Verify all text meets WCAG 2.1 AA contrast requirements
   - Test with color contrast analyzer
   - Check contrast in both light and dark modes

## User Management Workflow Testing

### Registration Testing

1. **Standard Registration**
   - Navigate to the registration page
   - Fill in the required fields (name, email, password)
   - Submit the form
   - Verify the user is created
   - Verify the user is redirected to the dashboard
   - Verify the user receives a welcome email

2. **Registration Validation**
   - Test with invalid email
   - Test with weak password
   - Test with mismatched password confirmation
   - Test with existing email
   - Verify appropriate error messages are displayed

3. **CMS Framework Registration**
   - Navigate to the registration page
   - Verify additional fields are displayed (username, first_name, last_name)
   - Fill in all required fields
   - Submit the form
   - Verify the user is created with the additional fields
   - Verify the user is redirected to the dashboard

### Login Testing

1. **Standard Login**
   - Navigate to the login page
   - Enter valid credentials
   - Submit the form
   - Verify the user is authenticated
   - Verify the user is redirected to the dashboard

2. **Login Validation**
   - Test with invalid email
   - Test with incorrect password
   - Test with non-existent user
   - Verify appropriate error messages are displayed

3. **Remember Me Functionality**
   - Login with "Remember Me" checked
   - Close the browser
   - Reopen the browser and navigate to the site
   - Verify the user is still authenticated

4. **CMS Framework Login**
   - Navigate to the login page
   - Verify the login form accepts username or email
   - Login with username
   - Verify the user is authenticated
   - Login with email
   - Verify the user is authenticated

### Password Management Testing

1. **Password Reset Request**
   - Navigate to the forgot password page
   - Enter a valid email
   - Submit the form
   - Verify a password reset link is sent
   - Verify the user is redirected to the login page with a success message

2. **Password Reset**
   - Click the password reset link in the email
   - Enter a new password
   - Submit the form
   - Verify the password is updated
   - Verify the user is redirected to the login page
   - Login with the new password
   - Verify the user is authenticated

3. **Password Change**
   - Login to the application
   - Navigate to the profile page
   - Enter the current password
   - Enter a new password
   - Submit the form
   - Verify the password is updated
   - Logout and login with the new password
   - Verify the user is authenticated

### Profile Management Testing

1. **Profile Information Update**
   - Login to the application
   - Navigate to the profile page
   - Update the profile information
   - Submit the form
   - Verify the profile information is updated
   - Refresh the page
   - Verify the updated information is displayed

2. **Email Verification**
   - Register a new user
   - Verify an email verification link is sent
   - Click the email verification link
   - Verify the email is marked as verified
   - Verify the user is redirected to the dashboard with a success message

3. **Account Deletion**
   - Login to the application
   - Navigate to the profile page
   - Click the delete account button
   - Confirm the deletion
   - Verify the account is deleted
   - Verify the user is redirected to the home page
   - Try to login with the deleted account
   - Verify the login fails

4. **CMS Framework Profile Management**
   - Login to the application
   - Navigate to the profile page
   - Verify additional fields are displayed (username, first_name, last_name)
   - Update the additional fields
   - Submit the form
   - Verify the additional fields are updated
   - Refresh the page
   - Verify the updated information is displayed

## CMS Framework Compatibility Testing

### Role and Permission Testing

1. **Role Assignment**
   - Login as an administrator
   - Navigate to the user management page
   - Assign a role to a user
   - Verify the role is assigned
   - Logout and login as the user
   - Verify the user has the assigned role

2. **Permission Assignment**
   - Login as an administrator
   - Navigate to the role management page
   - Assign permissions to a role
   - Verify the permissions are assigned
   - Logout and login as a user with the role
   - Verify the user has the assigned permissions

3. **Role-Based Access Control**
   - Login as a user with different roles
   - Navigate to different pages
   - Verify access is granted or denied based on roles
   - Test with the role-based-access component
   - Verify content is shown or hidden based on roles

4. **Permission-Based Access Control**
   - Login as a user with different permissions
   - Perform different actions
   - Verify actions are allowed or denied based on permissions
   - Test with the role-based-access component
   - Verify content is shown or hidden based on permissions

### Content Management Testing

1. **Content Type Configuration**
   - Login as an administrator
   - Navigate to the content type configuration
   - Create a new content type
   - Verify the content type is created
   - Add fields to the content type
   - Verify the fields are added
   - Configure statuses for the content type
   - Verify the statuses are configured

2. **Content Creation**
   - Login as a content editor
   - Navigate to the content management page
   - Create a new content item
   - Fill in the required fields
   - Submit the form
   - Verify the content item is created
   - Verify the content item has the default status

3. **Content Editing**
   - Login as a content editor
   - Navigate to the content management page
   - Edit an existing content item
   - Update the fields
   - Submit the form
   - Verify the content item is updated
   - Verify the updated fields are displayed

4. **Content Publishing**
   - Login as a content publisher
   - Navigate to the content management page
   - Find a draft content item
   - Publish the content item
   - Verify the content item status is changed to published
   - Verify the content item is visible on the frontend

5. **Content Deletion**
   - Login as a content editor
   - Navigate to the content management page
   - Delete a content item
   - Confirm the deletion
   - Verify the content item is deleted
   - Verify the content item is no longer visible on the frontend

6. **Content Manager Component**
   - Test the content-manager component with different content types
   - Test with different permissions
   - Verify the component shows or hides actions based on permissions
   - Test with different display options
   - Verify the component displays content correctly

### CMS Framework Views Testing

1. **Registration View**
   - Navigate to the registration page
   - Verify the view includes CMS-specific fields
   - Fill in all fields
   - Submit the form
   - Verify the user is created with the CMS-specific fields

2. **Profile View**
   - Login to the application
   - Navigate to the profile page
   - Verify the view includes CMS-specific fields
   - Update the CMS-specific fields
   - Submit the form
   - Verify the CMS-specific fields are updated

3. **Admin Dashboard**
   - Login as an administrator
   - Navigate to the admin dashboard
   - Verify the dashboard includes CMS-specific sections
   - Test navigation to different sections
   - Verify each section displays correctly

4. **Content Management Views**
   - Login as a content editor
   - Navigate to the content management views
   - Test content listing
   - Test content creation
   - Test content editing
   - Test content publishing
   - Test content deletion
   - Verify all views display correctly and function as expected