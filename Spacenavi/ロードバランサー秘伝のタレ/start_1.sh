# Linuxテキスト編集コマンドのすべて
# https://qiita.com/kenju/items/5777322e485a30aa6269
# http://raining.bear-life.com/linux/出力結果やテキストをファイルに書き出す方法
# Linux：出力結果やテキストをファイルに書き出す方法


# yumアプデ
yum update
yum grouplist
yum groupinstall "Development tools"
yum install wget
yum install man
yum install cyrus*
yum install mail
yum install telnet
yum install tree 

# ファイアーウォール
iptables -L
iptables -P FORWARD DROP
iptables -P INPUT ACCEPT
iptables -F
iptables -A INPUT -i lo -j ACCEPT
iptables -A INPUT -p tcp --dport 22 -j ACCEPT
iptables -A INPUT -p tcp --dport 20 -j ACCEPT
iptables -A INPUT -p tcp --dport 21 -j ACCEPT
iptables -A INPUT -p tcp --dport 60000:60030 -j ACCEPT
iptables -A INPUT -p tcp --dport 990 -j ACCEPT
iptables -A INPUT -p tcp --dport 989 -j ACCEPT
iptables -A INPUT -p tcp --dport 50021:50040  -j ACCEPT
iptables -A INPUT -p tcp --dport 25 -j ACCEPT
iptables -A INPUT -p tcp --dport 53 -j ACCEPT
iptables -A INPUT -p udp --dport 53 -j ACCEPT
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -j ACCEPT
iptables -A INPUT -p tcp --dport 110 -j ACCEPT
iptables -A INPUT -p tcp --dport 123 -j ACCEPT
iptables -A INPUT -p tcp --dport 587 -j ACCEPT
iptables -A INPUT -p tcp --dport 993 -j ACCEPT
iptables -A INPUT -p tcp --dport 995 -j ACCEPT
iptables -A INPUT -p icmp -j ACCEPT
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -P INPUT DROP
service iptables save
service iptables restart

# ftpインストール
yum install vsftpd
chkconfig vsftpd on

service vsftpd restart

# 電子証明書作成
cd /etc/pki/tls/certs/
make vsftpd.pem

service vsftpd restart

# httpインストール
yum install httpd
chkconfig httpd on
service httpd start

# phpインストール
rpm -Uvh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
yum install --enablerepo=remi --enablerepo=remi-php56 php php-devel php-mbstring php-mcrypt php-mysql php-phpunit-PHPUnit php-pecl-xdebug php-gd
php --version

# サーバーの再起動
service httpd restart

yum install mod_ssl
yum install openssl
# リネーム
mv /etc/httpd/conf.d/ssl.conf /etc/httpd/conf.d/ssl.conf_no

echo 'httpd.confを修正する'
echo 'vsftpd.confを修正する'
echo '/etc/httpd/confに秘密キーをアップする'