all: dominosa fifteen filling galaxies keen latin lightup loopy magnets map pattern pearl signpost singles slant solo tents towers unequal unruly
bin/.keep:
	mkdir bin
	touch bin/.keep

dominosa: c/dominosa.c
fifteen: c/fifteen.c
filling: c/filling.c
galaxies: c/galaxies.c
keen: c/keen.c
latin: c/latin.c
lightup: c/lightup.c
loopy: c/loopy.c
magnets: c/magnets.c
map: c/map.c
pattern: bin/patternsolver
	bin/patternsolver
bin/patternsolver: bin/.keep c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/pattern.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/pattern.c -o bin/patternsolver

pearl: c/pearl.c
signpost: c/signpost.c
singles: c/singles.c
slant: c/slant.c

solo: bin/solosolver
	bin/solosolver
bin/solosolver: bin/.keep c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/dsf.c c/divvy.c c/solo.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/dsf.c c/divvy.c c/solo.c -o bin/solosolver

tents: c/tents.c
towers: c/towers.c
unequal: c/unequal.c

unruly: bin/unrulysolver
	bin/unrulysolver
bin/unrulysolver: bin/.keep c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c -o bin/unrulysolver
