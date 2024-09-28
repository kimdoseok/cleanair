package main

import (
	"bufio"
	"compress/gzip"
	"fmt"
	"io/ioutil"
	"log"
	"os"
	"os/exec"
	"time"

	"github.com/jlaffaye/ftp"
)

const (
	ftphost string = "172.16.0.240"
	ftpuser string = "homehome"
	ftppass string = "k!md0$25K"
	basedir string = "/Backups"
	pghost  string = "172.16.0.235"
	pguser  string = "doseok"
	pgpass  string = "kim7795004"
	pgdata  string = "pypos"
)

func main() {
	fmt.Println("Starting...")
	filename, dtslice := getName()
	getBackupMysql(filename)
	compname := gzcompress(filename)
	upload(compname, dtslice)
	fmt.Println("Done.")
}

func setDirectory(conn *ftp.ServerConn, dtslice []int) string {
	dirname := ""
	for i, a := range dtslice {
		ls, err := conn.List(fmt.Sprintf("%s%s", basedir, dirname))
		if err != nil {
			log.Fatal(err)
		}

		dname := ""
		if i == 0 {
			dname = fmt.Sprintf("%04d", a)
		} else {
			dname = fmt.Sprintf("%02d", a)
		}

		notfound := true
		for _, b := range ls {
			//fmt.Println(b.Type, ftp.EntryTypeFolder, b.Name, dname)
			if b.Type == ftp.EntryTypeFolder && b.Name == dname {
				notfound = false
				break
			}
		}
		dirname += "/" + dname
		//fmt.Println("DIR: ", dirname, notfound)
		if notfound {
			pathname := fmt.Sprintf("%s%s", basedir, dirname)
			fmt.Println("PathName: ", pathname)
			err = conn.MakeDir(pathname)
			if err != nil {
				log.Println("MakeDir Error.")
			}
		}
	}
	return dirname
}

func upload(filename string, dtslice []int) {
	conn, err := ftp.Dial(fmt.Sprintf("%s:21", ftphost), ftp.DialWithTimeout(5*time.Second))
	if err != nil {
		log.Fatal(err)
	}

	err = conn.Login(ftpuser, ftppass)
	if err != nil {
		log.Fatal(err)
	}

	f, err := os.Open(filename)
	if err != nil {
		log.Fatal(err)
	}
	defer f.Close()

	dirname := setDirectory(conn, dtslice)

	//store
	reader := bufio.NewReader(f)
	err = conn.Stor(fmt.Sprintf("%s%s/%s", basedir, dirname, filename), reader)
	if err != nil {
		panic(err)
	}

	if err := conn.Quit(); err != nil {
		log.Fatal(err)
	}
	_ = os.Remove("./" + filename)

}

func getBackupPgsql(filename string) {
	cmd := exec.Command("pg_dump", fmt.Sprintf("--dbname=postgresql://%s:%s@%s:5432/%s", pguser, pgpass, pghost, pgdata), fmt.Sprintf("--file=./%s", filename))

	err := cmd.Run()
	if err != nil {
		log.Fatal(err)
	}
}

func getBackupMysql(filename string) {
	cmd := exec.Command("pg_dump", fmt.Sprintf("--dbname=postgresql://%s:%s@%s:5432/%s", pguser, pgpass, pghost, pgdata), fmt.Sprintf("--file=./%s", filename))

	err := cmd.Run()
	if err != nil {
		log.Fatal(err)
	}
}

func getName() (string, []int) {
	now := time.Now()
	dates := []int{now.Year(), int(now.Month())}
	dtstr := fmt.Sprintf("pgsql_pypos_%04d%02d%02d%02d%02d%02d.sql", now.Year(), int(now.Month()), now.Day(), now.Hour(), now.Minute(), now.Second())
	return dtstr, dates
}

func gzcompress(filename string) string {
	f, _ := os.Open("./" + filename)
	r := bufio.NewReader(f)
	content, _ := ioutil.ReadAll(r)
	gzippath := filename + ".gz"
	g, _ := os.Create(gzippath)
	w := gzip.NewWriter(g)
	w.Write(content)
	w.Close()
	_ = os.Remove("./" + filename)
	return gzippath
}

func ftpsample() {
	conn, err := ftp.Dial(fmt.Sprintf("%s:21", ftphost), ftp.DialWithTimeout(5*time.Second))
	if err != nil {
		log.Fatal(err)
	}

	err = conn.Login(ftpuser, ftppass)
	if err != nil {
		log.Fatal(err)
	}
	//store
	f, err := os.Open("C:/TDM-GCC-64/COPYING.MinGW-w64-runtime.txt")
	if err != nil {
		log.Fatal(err)
	}
	defer f.Close()

	ls, err := conn.List(fmt.Sprintf("%s/TDM-GCC-64", basedir))
	if err != nil {
		log.Fatal(err)
	}
	for i, l := range ls {
		fmt.Println(i, l.Name, l.Size, l.Target, l.Time, l.Type)
	}
	fmt.Println("LEN LIST: ", len(ls), ls)
	if len(ls) == 0 {
		err = conn.MakeDir(fmt.Sprintf("%s/TDM-GCC-64", basedir))
		if err != nil {
			panic(err)
		}
	}

	reader := bufio.NewReader(f)
	err = conn.Stor(fmt.Sprintf("%s%s", basedir, "/TDM-GCC-64/COPYING.MinGW-w64-runtime.txt"), reader)
	if err != nil {
		panic(err)
	}
	//conn.Stor("/TDM-GCC-64/COPYING.MinGW-w64-runtime.txt", f)

	// listing
	ls, err = conn.List(fmt.Sprintf("%s", basedir))
	if err != nil {
		log.Fatal(err)
	}
	for i, l := range ls {
		fmt.Println(i, l.Name, l.Size, l.Target, l.Time, l.Type)
	}

	//walk
	wk := conn.Walk(fmt.Sprintf("%s", basedir))
	for {
		if wk.Next() {
			fmt.Println("Path ", wk.Path())
			fmt.Println("Stat.Name ", wk.Stat().Name)
			fmt.Println("Stat.Size ", wk.Stat().Size)
			fmt.Println("Stat.Target", wk.Stat().Target)
			fmt.Println("Stat.Time ", wk.Stat().Time)
			fmt.Println("Stat.Type", wk.Stat().Type)
			fmt.Println()
		} else {
			break
		}
	}

	if err := conn.Quit(); err != nil {
		log.Fatal(err)
	}

}
