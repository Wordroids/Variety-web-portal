# Clear problematic COMPOSER environment variable
$env:COMPOSER = ""

# Navigate to project directory
Set-Location "C:\Users\HI\Desktop\Variety Web Portal\Variety-web-portal"

# Run composer dev
composer run dev
