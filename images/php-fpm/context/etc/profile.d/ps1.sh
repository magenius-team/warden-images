if [[ "$EUID" != "0" && "$UID" != "0" ]]; then
    export PS1='\[\033[0;36m\]\u@\h\[\033[0m\]:\[\033[0;37m\]\w\[\033[0m\]$ '
else
    export PS1='\[\033[0;5m\]\u@\h\[\033[0m\]:\[\033[0;31m\]\w\[\033[0m\]# '
fi
