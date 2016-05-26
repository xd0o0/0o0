Import-Module ActiveDirectory

$name=$args[1] #name #$args[1]
$plainPass=$args[2] #$args[2]
$password=ConvertTo-SecureString -AsPlainText -Force -String $plainPass 

if ($args[0] -eq 0)
{
New-ADUser -Enabled 1 -Name $name -AccountPassword $password -DisplayName $name -GivenName $name -UserPrincipalName $name@xd1.com -SamAccountName $name -Surname $name -PasswordNeverExpires $true
}
elseif ($args[0] -eq 1)
{
Get-ADUser -Identity $name|Set-ADAccountPassword -Reset -NewPassword $password
Get-ADUser -Identity $name|Set-ADUser -ChangePasswordAtLogon $false
}