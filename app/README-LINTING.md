# Static Analysis Tools

This project uses PHP CS Fixer and PHPStan to ensure code quality and maintain consistent coding style.

## PHP CS Fixer

PHP CS Fixer is a tool that automatically fixes PHP code to comply with coding standards. The configuration is located in the `.php-cs-fixer.dist.php` file.

### Available Commands

```bash
# Check for style issues without fixing them
composer cs:dry

# Automatically fix style issues
composer cs
```

## PHPStan

PHPStan is a static analyzer that finds errors in your code without running it. The configuration is located in the `phpstan.neon` file.

### Available Commands

```bash
# Run static analysis
composer stan
```

## Combined Commands

```bash
# Check for style issues and run static analysis
composer lint

# Fix style issues and run static analysis
composer fix
```

## Git Hooks Integration

It is recommended to set up Git Hooks to run these tools automatically before each commit. You can use [Husky](https://github.com/typicode/husky) for PHP or configure hooks manually.

## Analysis Level

PHPStan is configured with analysis level 5, which is a good starting point for Laravel projects. If you want to increase the strictness of the analysis, you can increase this level in the `phpstan.neon` file.
