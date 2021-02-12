<?php

return [
    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'login' => [
        'title' => 'Log in',
        'form' => [
            'title' => 'Login',
            'text' => [
                'not_member' => 'Don\'t have an account?',
                'sign_up' => 'Create account',
            ],
            'fields' => [
                'email' => [
                    'label' => 'Email address',
                ],
                'password' => [
                    'label' => 'Password',
                ],
            ],
            'links' => [
                'forgot_password' => 'Forgot your password?',
            ],
            'buttons' => [
                'submit' => [
                    'text' => 'Log in',
                ],
            ],
        ],
    ],
    'password_request' => [
        'title' => 'Password recovery',
        'fields' => [
            'email' => [
                'label' => 'Email',
            ],
        ],
        'text' => [
            'forgot_password' => 'Forgot your password?',
            'log_in' => 'Already have an account?',
            'register' => 'Don\'t have an account?',
            'before_click' => 'By clicking the Reset button, you agree to our',
            'and' => 'and',
        ],
        'links' => [
            'log_in' => 'Log in',
            'register' => 'Sign up',
            'terms' => 'Terms & Conditions',
            'policy' => 'Privacy policy',
        ],
        'buttons' => [
            'send' => [
                'text' => 'Reset',
            ],
        ],
        'email' => [
            'subject' => 'Reset password notification',
            'line' => [
                'description' => 'You are receiving this email because we received a password reset request for your account.',
                'unknown_request' => 'If you did not request a password reset, no further action is required.',
            ],
            'button' => [
                'text' => 'Reset password',
            ],
        ],
    ],
    'password_reset' => [
        'title' => 'Password recovery',
        'text' => [
            'log_in' => 'Already have an account?',
            'register' => 'Don\'t have an account?',
            'before_click' => 'By clicking the Change my password button, you agree to our',
            'and' => 'and',
        ],
        'links' => [
            'log_in' => 'Log in',
            'register' => 'Sign up',
            'terms' => 'Terms & Conditions',
            'policy' => 'Privacy policy',
        ],
        'form' => [
            'fields' => [
                'password' => [
                    'label' => 'New password',
                ],
                'password_confirmation' => [
                    'label' => 'Confirm new password',
                ],
            ],
            'buttons' => [
                'reset' => [
                    'text' => 'Change my password',
                ],
            ],
        ],
    ],
    'password_request_success' => [
        'title' => 'Password recovery',
        'text' => [
            'thank_you' => 'Thank you! Please check your email inbox for further instructions.',
        ],
        'links' => [
            'home' => 'Home',
        ],
    ],
    'register' => [
        'title' => 'Sign up',
        'form' => [
            'text' => [
                'have_account' => 'Already have an account?',
                'before_click' => 'By clicking the Sign up button, you agree to our',
                'and' => 'and',
            ],
            'fields' => [
                'name' => [
                    'label' => 'Name*',
                ],
                'phone' => [
                    'label' => 'Phone number*',
                ],
                'email' => [
                    'label' => 'Email*',
                ],
                'password' => [
                    'label' => 'Password',
                ],
                'confirm_password' => [
                    'label' => 'Confirm password',
                ],
            ],
            'links' => [
                'terms' => 'Terms & Conditions',
                'policy' => 'Privacy policy',
                'log_in' => 'Log in',
            ],
            'buttons' => [
                'submit' => [
                    'text' => 'Sign up',
                ],
            ],
        ],
        'verify' => [
            'title' => 'Before proceeding, please check your email for a verification link.',
            'email' => [
                'subject' => 'Verify Email Address',
                'line' => [
                    'description' => 'Verify email address,',
                ],
                'buttons' => [
                    'submit' => [
                        'text' => 'Verify Email',
                    ],
                ],
            ],
            'error' => 'Mail verification error, please try register again.',
        ],
    ],
];
