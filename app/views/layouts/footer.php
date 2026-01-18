        </main>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo APP_NAME; ?></h3>
                <p>Personal finance management made simple. Track your income and expenses with ease.</p>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/income/index">Income</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/expense/index">Expenses</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/category/index">Categories</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>/help">Help Center</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact">Contact</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/privacy">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Account</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>/user/settings">Settings</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/user/profile">Profile</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/auth/logout">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
            <p>Made with ❤️ for smart financial planning</p>
        </div>
    </footer>

    <style>
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: auto;
            padding: 3rem 2rem 1rem;
            border-top: 2px solid rgba(255, 255, 255, 0.1);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto 2rem;
        }

        .footer-section h3,
        .footer-section h4 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .footer-section p {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.9;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: white;
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .footer-bottom p {
            margin: 0.25rem 0;
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            footer {
                padding: 2rem 1rem 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .footer-section {
                text-align: center;
            }

            .footer-section ul {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    <!-- Main Application JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
</body>
</html>
