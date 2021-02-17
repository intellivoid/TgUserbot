clean:
	rm -rf build

update:
	ppm --generate-package="src/TgUserbot"

build:
	mkdir build
	ppm --no-intro --compile="src/TgUserbot" --directory="build"

install:
	ppm --no-prompt --fix-conflict --branch="production" --install="build/net.intellivoid.tguserbot.ppm"