clean:
	rm -rf build

update:
	ppm --generate-package="src/TgUserbot"

build:
	mkdir build
	ppm --no-intro --compile="src/TgUserbot" --directory="build"

install:
	ppm --no-prompt --fix-conflict --branch="production" --skip-dependencies --install="build/net.intellivoid.tguserbot.ppm"
