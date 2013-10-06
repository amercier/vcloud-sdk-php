
.PHONY: api

SKINPARAMS := \
	-SsequenceArrowColor=\#000000 \
	-SsequenceActorBorderColor=\#000000 \
	-SsequenceActorBackgroundColor=\#cee7f6 \
	-SsequenceActorFontSize=13 \
	-SsequenceActorFontStyle=bold \
	-SsequenceArrowColor=\#000000 \
	-SsequenceActorBorderThickness=2 \
	-SsequenceParticipantBorderColor=\#000000 \
	-SsequenceParticipantBackgroundColor=\#eef5f9/cee7f6 \
	-SsequenceParticipantFontSize=13 \
	-SsequenceParticipantFontStyle=bold \
	-SsequenceLifeLineBorderColor=\#000000 \
	-SsequenceLifeLineBackgroundColor=\#cee7f6 \
	-SactivityArrowColor=\#000000 \
	-SactivityBorderColor=\#000000 \
	-SactivityBackgroundColor=\#eef5f9/cee7f6 \
	-SactivityFontSize=13 \
	-SclassArrowColor=\#000000 \
	-SclassBackgroundColor=\#eef5f9/cee7f6 \
	-SclassBorderColor=\#000000 \
	-SpackageBorderColor=\#666666 \
	-SpackageBackgroundColor=\#ffffff \
	-SpackageFontSize=13 \
	-SpackageFontColor=\#666666 \
	-ScircledCharacterFontColor=black \
	-ScircledCharacterFontSize=8 \
	-ScircledCharacterFontStyle=bold \
	-ScircledCharacterFontName=Arial \
	-ScircledCharacterRadius=8 \
	-ScomponentArrowColor=\#000000 \
	-ScomponentInterfaceBackgroundColor=\#cee7f6 \
	-ScomponentInterfaceBorderColor=\#000000 \
	-ScomponentBackgroundColor=\#eef5f9/cee7f6 \
	-ScomponentBorderColor=\#000000 \
	-ScomponentArrowColor=\#000000 \
	-ScomponentArrowFontColor=\#000000

all: lib/plantuml.jar
	ls img/*.plantuml | while read filename; do echo "---------- $${filename/.plantuml/.png} ----------"; make "$${filename/.plantuml/.png}"; done
	#
	# Generated UML diagrams successfully

clean:
	rm -f img/*.svg img/*.png
		rm -Rf api/*

lib/plantuml.jar: lib
	wget --no-verbose -O lib/plantuml.jar http://heanet.dl.sourceforge.net/project/plantuml/plantuml.jar

lib:
	mkdir lib

img/%.png: img/%.plantuml
	java -jar lib/plantuml.jar -tpng $(SKINPARAMS) $^

img/%.svg: img/%.plantuml
	java -jar lib/plantuml.jar -tsvg $(SKINPARAMS) $^ && sed -i -e 's/<svg/<svg font-family="Helvetica"/' $@

api:
	phpdoc --directory ../library/ --target api --title "vCloud PHP SDK Documentation" --template responsive --sourcecode

watch:
	fswatch img "make && ./img/*.png"
