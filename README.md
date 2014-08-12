hc — light and extendable health checker
========================================

 The **hc** is lightweight health checker written in php. **hc** can check health of http services, 
 popular databases (mysql, postgres) and redis. Default output format is tab-delimited strings
 to allow easily grep and cut results.

Quick start
-----------

1. Create config file `config.yml` with check rules:

   ```
   ---
   - name: google
     type: http
     request: { url: http://google.com/ }
     
   - name: bing
     type: http
     request: 
       url: http://bing.com:1234/
       timeout: 1
   ```
   
2. Run **hc**:

   ```
   hc config.yml
   ```
   
   You should see result (tab-delimited):
   
   ```
   ok	google
   fail	bing	connection refused
   ```

Usage
-----

```
hc [-th] [-o format] <config-file> 
```

* **-h:** print command help and list of available assertions;
* **-t:** don't run, just test the configuration file;
* **-o format:** set format of output; default is "tsv". Allowed: "tsv", "json".

See descriptive examples of configuration file and output in `examples` directory.