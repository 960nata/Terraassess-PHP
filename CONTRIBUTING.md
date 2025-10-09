# Contributing to Elass 2

Thank you for your interest in contributing to Elass 2! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### 1. Fork the Repository
- Click the "Fork" button on the GitHub repository page
- Clone your fork locally:
```bash
git clone https://github.com/yourusername/elass2.git
cd elass2
```

### 2. Set Up Development Environment
```bash
# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run dev
```

### 3. Create a Branch
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/your-bug-fix
```

### 4. Make Changes
- Write clean, readable code
- Follow the existing code style
- Add tests for new features
- Update documentation if needed

### 5. Test Your Changes
```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=YourTestClass

# Check code style
./vendor/bin/pint --test
```

### 6. Commit Changes
```bash
git add .
git commit -m "feat: add new feature description"
```

### 7. Push and Create Pull Request
```bash
git push origin feature/your-feature-name
```

Then create a Pull Request on GitHub.

## 📝 Code Style Guidelines

### PHP (Laravel)
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting
- Write descriptive variable and function names
- Add proper PHPDoc comments

### JavaScript (Vue.js)
- Use ESLint configuration
- Follow Vue.js style guide
- Use meaningful component names
- Add JSDoc comments for complex functions

### CSS/SCSS
- Use Bootstrap 5 classes when possible
- Follow BEM methodology for custom CSS
- Use SCSS variables for consistent theming

## 🧪 Testing Guidelines

### Unit Tests
- Test individual methods and functions
- Mock external dependencies
- Aim for high code coverage
- Place tests in `tests/Unit/` directory

### Feature Tests
- Test complete user workflows
- Test API endpoints
- Test database interactions
- Place tests in `tests/Feature/` directory

### Test Naming
```php
// Good
public function it_can_create_tugas_with_valid_data()

// Bad
public function test1()
```

## 📋 Pull Request Guidelines

### Before Submitting
- [ ] Code follows style guidelines
- [ ] All tests pass
- [ ] New features have tests
- [ ] Documentation is updated
- [ ] No breaking changes (or clearly documented)

### PR Description Template
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Unit tests added/updated
- [ ] Feature tests added/updated
- [ ] Manual testing completed

## Screenshots (if applicable)
Add screenshots to help explain your changes

## Checklist
- [ ] My code follows the style guidelines
- [ ] I have performed a self-review
- [ ] I have commented my code
- [ ] I have made corresponding changes to documentation
```

## 🐛 Reporting Bugs

### Bug Report Template
```markdown
**Describe the bug**
A clear description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- OS: [e.g. Windows, macOS, Linux]
- Browser: [e.g. Chrome, Firefox, Safari]
- Version: [e.g. 22]

**Additional context**
Add any other context about the problem here.
```

## ✨ Suggesting Features

### Feature Request Template
```markdown
**Is your feature request related to a problem?**
A clear description of what the problem is.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
A clear description of any alternative solutions.

**Additional context**
Add any other context or screenshots about the feature request.
```

## 📚 Development Setup

### Required Tools
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+
- Git

### IDE Recommendations
- PhpStorm
- VS Code with Laravel extensions
- Sublime Text with Laravel packages

### Useful Commands
```bash
# Code formatting
./vendor/bin/pint

# Run tests with coverage
php artisan test --coverage

# Clear all caches
php artisan optimize:clear

# Generate IDE helper files
php artisan ide-helper:generate
php artisan ide-helper:models
```

## 🏷️ Commit Message Convention

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Types
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples
```
feat(auth): add two-factor authentication
fix(api): resolve validation error in tugas endpoint
docs: update installation instructions
test: add unit tests for TugasService
```

## 🤔 Questions?

If you have any questions about contributing, please:
1. Check existing issues and discussions
2. Create a new issue with the "question" label
3. Contact maintainers directly

## 🙏 Thank You

Thank you for contributing to Elass 2! Your contributions help make this project better for everyone.
