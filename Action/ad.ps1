$uname="administrator" #administrator为用户名
$pwd=ConvertTo-SecureString "@WSX3edc4" -AsPlainText -Force; #@WSX3edc4为密码
$cred=New-Object System.Management.Automation.PSCredential($uname,$pwd); #创建自动认证对象
$pcname="172.16.80.248"
$session = New-PSSession -ComputerName $pcname -Credential $cred #登录


#Invoke-Command -scriptblock {ipconfig} -Credential $cred -ComputerName $pcname

#Invoke-Command -scriptblock {Import-Module ActiveDirectory} -Session $session

Invoke-Command -FilePath D:\workspaces\PHP\Action\aduser.ps1 -argumentlist $args[0],$args[1],$args[2] -Session $session
#Import-Module ActiveDirectory
#Enter-PSSession -Session $session

#$name="GlenJohn" #name #$args[0]
#$plainPass='@WSX3edc' #$args[1]
#$password=ConvertTo-SecureString -AsPlainText -Force -String $plainPass 

#New-ADUser -Enabled 1 -Name $name -AccountPassword $password -DisplayName $name -GivenName $name -UserPrincipalName $name@xd1.com -SamAccountName $name -Surname $name -PasswordNeverExpires $true

#Get-ADUser -Identity $name|Set-ADAccountPassword -Reset -NewPassword $password
#Get-ADUser -Identity $name|Set-ADUser -ChangePasswordAtLogon $false
#echo $args[0],$args[1],$args[2]
Remove-PSSession -Session $session

#Exit-PSSession
#Invoke-Command -scriptblock {ipconfig} -Credential $cred -ComputerName $pcname #-scriptblock是用来执行单个命令
#Invoke-Command -FilePath C:\runme.ps1 -Credential $cred -ComputerName $pcname #-filepath是用来执行脚本文件中的命令


