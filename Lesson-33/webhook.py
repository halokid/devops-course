# -*-  coding: utf8 -*-

import time
from flask import Flask


app = Flask(__name__)

@app.route("/proms/alert")
def promsAlert():
  t = time.time()
  localTime = time.localtime(t) 
  st = time.strftime("%Y-%m-%d %H:%M:%S", localTime)
  return "[收到告警] -------- 时间为" + st


if __name__ == "__main__":
  # app.run()
  app.run(port=5000, debug=True)


