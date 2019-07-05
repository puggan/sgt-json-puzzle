unruly: bin/unrulysolver
bin/unrulysolver: bin/
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c -o bin/unrulysolver
bin/:
	mkdir bin
