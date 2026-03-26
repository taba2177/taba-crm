<?php

return [
    // Contact Form
    'contact' => [
        'name' => 'Name',
        'email' => 'Email',
        'message' => 'Message',
        'quiz' => 'What is 3 + 4?',
        'submit' => 'Send Message',
        'success' => 'Your message has been sent successfully!',
        'error' => 'An error occurred. Please try again.',
        'quiz_error' => 'The answer is not correct',
    ],

    // Quote Request Form
    'quote' => [
        'name' => 'Full Name',
        'phone' => 'Mobile Number',
        'district' => 'District',
        'notes' => 'Notes',
        'width' => 'Width',
        'height' => 'Height',
        'curtain_type' => 'Curtain Type',
        'room_name' => 'Room Name',
        'submit' => 'Request Quote',
        'success' => 'Your request has been submitted successfully!',
        'error' => 'An error occurred while submitting. Please try again.',
        'recaptcha_wait' => 'Please wait for reCAPTCHA verification.',
        'recaptcha_failed' => 'reCAPTCHA verification failed. Please try again.',
    ],

    // Franchise Form
    'franchise' => [
        'full_name' => 'Full Name',
        'age' => 'Age',
        'gender' => 'Gender',
        'education' => 'Education Level',
        'city' => 'City',
        'branches' => 'Number of Branches',
        'phone' => 'Phone Number',
        'email' => 'Email',
        'country' => 'Country',
        'province' => 'Province',
        'business_experience' => 'Business Experience',
        'restaurant_experience' => 'Restaurant Experience',
        'investment_amount' => 'Investment Amount',
        'has_loans' => 'Existing Loans',
        'submit' => 'Submit Application',
        'success' => 'Your application has been submitted successfully! We will contact you soon.',
        'error' => 'An error occurred during submission. Please try again.',
        'recaptcha_required' => 'reCAPTCHA verification is required',
    ],

    // Validation Messages
    'validation' => [
        'name_required' => 'Name is required.',
        'name_regex' => 'Please enter a valid name containing only letters.',
        'phone_required' => 'Mobile number is required.',
        'phone_regex' => 'Phone number must consist of 10 digits.',
        'email_required' => 'Email is required.',
        'message_required' => 'Message is required.',
        'quiz_required' => 'Please answer the question.',
        'recaptcha_required' => 'reCAPTCHA verification is required.',
        'age_required' => 'Age is required.',
        'gender_required' => 'Gender is required.',
        'education_required' => 'Education level is required.',
        'city_required' => 'City is required.',
        'branches_required' => 'Number of branches is required.',
        'country_required' => 'Country is required.',
        'province_required' => 'Province is required.',
        'investment_amount_required' => 'Investment amount is required.',
        'has_loans_required' => 'Loan status is required.',
    ],

    // Common
    'placeholder' => [
        'name' => 'Enter your name',
        'email' => 'Enter your email',
        'phone' => 'Enter your phone number',
        'message' => 'Enter your message',
    ],

    // SEO
    'seo' => [
        'franchise_title' => 'Franchise Opportunity | Join Our Growing Brand',
        'franchise_description' => 'Join our family and get a distinctive franchise. Leading investment opportunities in the industry.',
        'franchise_name' => 'Commercial Franchise',
        'franchise_schema_description' => 'Distinctive franchise opportunities - Invest with a leading brand.',
        'default_description' => ':name - Your trusted partner for professional services and solutions.',
    ],
];
